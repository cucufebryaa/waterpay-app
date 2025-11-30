<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Pemakaian;
use App\Models\Pembayaran;

class PelangganTagihanController extends Controller
{
    private function getXenditKey() {
        return env('XENDIT_SECRET_KEY'); 
    }

    /**
     * Halaman Utama Tagihan
     */
    public function index()
    {
        $user = Auth::user();
        
        // Pastikan user terhubung ke pelanggan
        if (!$user->pelanggan) {
            return back()->with('error', 'Akun User belum terhubung dengan Data Pelanggan.');
        }

        $pelangganId = $user->pelanggan->id;

        // 1. Ambil Tagihan (Belum Lunas)
        // Filter berdasarkan status_pembayaran di tabel pemakaian
        $tagihanBelumBayar = Pemakaian::with(['kode_product'])
            ->where('id_pelanggan', $pelangganId)
            ->whereIn('status_pembayaran', ['belum_bayar', 'pending'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Hitung Estimasi Denda & Total (Hanya untuk Display)
        foreach ($tagihanBelumBayar as $tagihan) {
            $this->kalkulasiTotalDisplay($tagihan);
        }

        // 3. Ambil Riwayat Pembayaran (Dari tabel Pembayaran yang sukses)
        $riwayatPembayaran = Pembayaran::with(['pemakaian'])
            ->where('id_pelanggan', $pelangganId)
            ->where('status', 'success')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        return view('pelanggan.tagihan', compact('tagihanBelumBayar', 'riwayatPembayaran'));
    }

    /**
     * Proses Membuat Pembayaran (Create Invoice Xendit)
     */
    public function createPayment($idPemakaian)
    {
        $user = Auth::user();
        
        // 1. Validasi Tagihan
        $pemakaian = Pemakaian::with(['pelanggan', 'kode_product', 'company'])->find($idPemakaian);

        if (!$pemakaian || $pemakaian->id_pelanggan != $user->pelanggan->id) {
            return back()->with('error', 'Tagihan tidak ditemukan atau bukan milik Anda.');
        }

        // Cek jika status di pemakaian sudah lunas
        if ($pemakaian->status_pembayaran == 'lunas') {
            return back()->with('success', 'Tagihan ini sudah lunas.');
        }

        // Cek pembayaran pending
        $existingPayment = Pembayaran::where('id_pemakaian', $pemakaian->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingPayment && $existingPayment->payment_url) {
             return redirect($existingPayment->payment_url);
        }

        try {
            DB::beginTransaction();

            $rincian = $this->hitungRincianBiaya($pemakaian);
            $externalId = 'INV-' . $pemakaian->id . '-' . time();

            // 4. Buat Record di Tabel Pembayaran (Status Pending)
            $pembayaran = Pembayaran::create([
                'id_pelanggan'   => $pemakaian->id_pelanggan,
                'id_pemakaian'   => $pemakaian->id,
                'id_company'     => $pemakaian->id_company,
                'xendit_external_id' => $externalId,
                'jumlah_tagihan' => $rincian['tagihan_pokok'],
                'denda'          => $rincian['denda'],
                'biaya_admin'    => $rincian['biaya_admin'],
                'total_bayar'    => $rincian['total_akhir'],
                'status'         => 'pending',
                'payment_channel'=> 'XENDIT_CHECKOUT'
            ]);

            // 5. Request ke API Xendit
            $params = [ 
                'external_id' => $externalId,
                'amount' => $rincian['total_akhir'],
                'description' => 'Pembayaran Air Periode ' . $pemakaian->created_at->format('M Y'),
                'invoice_duration' => 86400,
                'customer' => [
                    'given_names' => $user->pelanggan->nama,
                    'mobile_number' => $user->pelanggan->no_hp,
                    'email' => $user->email
                ],
                'success_redirect_url' => route('pelanggan.tagihan.index'),
                'failure_redirect_url' => route('pelanggan.tagihan.index')
            ];

            $response = Http::withBasicAuth($this->getXenditKey(), '')
                ->post('https://api.xendit.co/v2/invoices', $params);

            if ($response->successful()) {
                $xenditData = $response->json();
                
                $pembayaran->update([
                    'xendit_id'   => $xenditData['id'],
                    'payment_url' => $xenditData['invoice_url']
                ]);

                // Update juga status pemakaian jadi pending
                $pemakaian->update(['status_pembayaran' => 'pending']);

                DB::commit();

                return redirect($xenditData['invoice_url']);
            } else {
                throw new \Exception('Gagal menghubungi Xendit: ' . $response->body());
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Webhook/Callback dari Xendit
     */
    public function callbackXendit(Request $request)
    {
        // Validasi Token dimatikan sementara untuk testing (sesuai request sebelumnya)
        // $xenditToken = $request->header('x-callback-token');
        // $myCallbackToken = env('XENDIT_CALLBACK_TOKEN');
        // if ($xenditToken !== $myCallbackToken) { return response()->json(['message' => 'Invalid Token'], 403); }

        $data = $request->all();

        $externalId = $data['external_id'] ?? null;
        $status     = $data['status'] ?? null;

        if (!$externalId) {
            return response()->json(['message' => 'Invalid Data'], 400);
        }

        // Cari data pembayaran berdasarkan ID external invoice
        $pembayaran = Pembayaran::where('xendit_external_id', $externalId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Transaction not found in local DB'], 200);
        }

        if ($pembayaran->status == 'success') {
            return response()->json(['message' => 'Already paid'], 200);
        }

        try {
            DB::beginTransaction();

            if ($status == 'PAID' || $status == 'SETTLED') {
                // 1. UPDATE TABEL PEMBAYARAN
                $pembayaran->update([
                    'status' => 'success',
                    'tanggal_bayar' => Carbon::parse($data['paid_at'] ?? now()),
                    'payment_channel' => $data['payment_method'] ?? 'XENDIT'
                ]);

                // 2. UPDATE TABEL PEMAKAIAN (PENTING!)
                // Kita cari induk pemakaiannya dan ubah statusnya jadi LUNAS
                $pemakaian = Pemakaian::find($pembayaran->id_pemakaian);
                
                if ($pemakaian) {
                    $pemakaian->update([
                        'status_pembayaran' => 'lunas', // Ubah status jadi Lunas agar hilang dari list tagihan
                        'total_akhir' => $pembayaran->total_bayar, 
                        'tgl_bayar' => $pembayaran->tanggal_bayar
                    ]);
                } else {
                    Log::error("Data Pemakaian tidak ditemukan untuk pembayaran ID: " . $pembayaran->id);
                }

            } else if ($status == 'EXPIRED') {
                // Jika expired
                $pembayaran->update(['status' => 'expired']);
                
                // Kembalikan status pemakaian jadi belum bayar agar bisa dibuat ulang
                $pemakaian = Pemakaian::find($pembayaran->id_pemakaian);
                if ($pemakaian) {
                    $pemakaian->update(['status_pembayaran' => 'belum_bayar']);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Xendit Callback Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing'], 500);
        }
    }

    private function hitungRincianBiaya($pemakaian)
    {
        $tagihanPokok = $pemakaian->total_tagihan;
        $denda = 0;
        $aturanHarga = $pemakaian->kode_product; 
        
        if ($aturanHarga) {
            $tanggalPencatatan = Carbon::parse($pemakaian->created_at);
            $tglBatas = (int) ($aturanHarga->batas_waktu_denda ?? 20); 
            $jatuhTempo = $tanggalPencatatan->copy()->addMonth()->setDay($tglBatas);

            if (Carbon::now()->greaterThan($jatuhTempo)) {
                $denda = $aturanHarga->denda ?? 0;
            }
        }

        $biayaAdmin = 0; 
        $totalAkhir = $tagihanPokok + $denda + $biayaAdmin;

        return [
            'tagihan_pokok' => $tagihanPokok,
            'denda' => $denda,
            'biaya_admin' => $biayaAdmin,
            'total_akhir' => $totalAkhir
        ];
    }

    private function kalkulasiTotalDisplay($tagihan)
    {
        $rincian = $this->hitungRincianBiaya($tagihan);
        
        $tagihan->denda_est = $rincian['denda'];
        $tagihan->biaya_admin_est = $rincian['biaya_admin'];
        $tagihan->total_bayar_est = $rincian['total_akhir'];
        
        $aturanHarga = $tagihan->kode_product;
        $tglBatas = (int) ($aturanHarga->batas_waktu_denda ?? 20);
        $tagihan->jatuh_tempo_display = Carbon::parse($tagihan->created_at)
            ->addMonth()
            ->setDay($tglBatas)
            ->translatedFormat('d F Y');
        
        $tagihan->is_telat = ($rincian['denda'] > 0);
    }
}
