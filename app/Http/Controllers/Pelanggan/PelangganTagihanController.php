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
        
        Log::info('PAGE_VIEW: User mengakses halaman tagihan.', ['user_id' => $user->id, 'email' => $user->email]);

        // Pastikan user terhubung ke pelanggan
        if (!$user->pelanggan) {
            Log::warning('PAGE_VIEW_FAIL: Akun user belum terhubung dengan data pelanggan.', ['user_id' => $user->id]);
            return back()->with('error', 'Akun User belum terhubung dengan Data Pelanggan.');
        }

        $pelangganId = $user->pelanggan->id;

        // 1. Ambil Tagihan (Belum Lunas)
        $tagihanBelumBayar = Pemakaian::with(['kode_product'])
            ->where('id_pelanggan', $pelangganId)
            ->whereIn('status_pembayaran', ['belum_bayar', 'pending'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        Log::info('PAGE_VIEW: Mengambil data tagihan belum bayar.', [
            'pelanggan_id' => $pelangganId, 
            'jumlah_tagihan' => $tagihanBelumBayar->count()
        ]);

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

        Log::info('PAGE_VIEW: Mengambil riwayat pembayaran.', [
            'pelanggan_id' => $pelangganId, 
            'jumlah_riwayat' => $riwayatPembayaran->count()
        ]);

        return view('pelanggan.tagihan', compact('tagihanBelumBayar', 'riwayatPembayaran'));
    }

    /**
     * Proses Membuat Pembayaran (Create Invoice Xendit)
     */
    public function createPayment($idPemakaian)
    {
        $user = Auth::user();
        Log::info('CREATE_PAYMENT: Memulai proses pembuatan pembayaran.', ['user_id' => $user->id, 'id_pemakaian' => $idPemakaian]);
        
        // 1. Validasi Tagihan
        $pemakaian = Pemakaian::with(['pelanggan', 'kode_product', 'company'])->find($idPemakaian);

        if (!$pemakaian || $pemakaian->id_pelanggan != $user->pelanggan->id) {
            Log::warning('CREATE_PAYMENT_FAIL: Tagihan tidak ditemukan atau bukan milik user.', ['user_id' => $user->id, 'id_pemakaian' => $idPemakaian]);
            return back()->with('error', 'Tagihan tidak ditemukan atau bukan milik Anda.');
        }

        // Cek jika status di pemakaian sudah lunas
        if ($pemakaian->status_pembayaran == 'lunas') {
            Log::info('CREATE_PAYMENT_SKIP: Tagihan sudah lunas.', ['id_pemakaian' => $idPemakaian]);
            return back()->with('success', 'Tagihan ini sudah lunas.');
        }

        // Cek pembayaran pending
        $existingPayment = Pembayaran::where('id_pemakaian', $pemakaian->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if ($existingPayment && $existingPayment->payment_url) {
             Log::info('CREATE_PAYMENT_EXIST: Mengalihkan ke URL pembayaran yang sudah ada (Pending).', [
                 'id_pemakaian' => $idPemakaian, 
                 'existing_payment_id' => $existingPayment->id
             ]);
             return redirect($existingPayment->payment_url);
        }

        try {
            DB::beginTransaction();
            Log::info('CREATE_PAYMENT: Database Transaction Started.');

            $rincian = $this->hitungRincianBiaya($pemakaian);
            Log::info('CREATE_PAYMENT: Rincian biaya dihitung.', ['rincian' => $rincian]);

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

            Log::info('CREATE_PAYMENT: Record pembayaran dibuat di DB lokal.', ['payment_id' => $pembayaran->id, 'external_id' => $externalId]);

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

            Log::info('CREATE_PAYMENT: Mengirim request ke Xendit API.', ['payload' => $params]);

            $response = Http::withBasicAuth($this->getXenditKey(), '')
                ->post('https://api.xendit.co/v2/invoices', $params);

            Log::info('CREATE_PAYMENT: Response dari Xendit diterima.', [
                'status_code' => $response->status(),
                'response_body' => $response->json()
            ]);

            if ($response->successful()) {
                $xenditData = $response->json();
                
                $pembayaran->update([
                    'xendit_id'   => $xenditData['id'],
                    'payment_url' => $xenditData['invoice_url']
                ]);

                // Update juga status pemakaian jadi pending
                $pemakaian->update(['status_pembayaran' => 'pending']);

                DB::commit();
                Log::info('CREATE_PAYMENT: Sukses! Transaksi di-commit dan redirect ke Xendit.', ['invoice_url' => $xenditData['invoice_url']]);

                return redirect($xenditData['invoice_url']);
            } else {
                Log::error('CREATE_PAYMENT_ERROR: Gagal response dari Xendit.', ['body' => $response->body()]);
                throw new \Exception('Gagal menghubungi Xendit: ' . $response->body());
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('CREATE_PAYMENT_EXCEPTION: Terjadi kesalahan Exception.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Webhook/Callback dari Xendit
     */
    public function callbackXendit(Request $request)
    {
        $data = $request->all();

        // LOGGING AWAL: Menerima data mentah dari Xendit
        Log::info('CALLBACK_RECEIVED: Menerima webhook dari Xendit.', ['request_data' => $data]);
        
        $externalId = $data['external_id'] ?? null;
        $status     = $data['status'] ?? null;

        if (!$externalId) {
            Log::warning('CALLBACK_INVALID: External ID is missing.');
            return response()->json(['message' => 'Invalid Data'], 400);
        }

        // Cari data pembayaran berdasarkan ID external invoice
        $pembayaran = Pembayaran::where('xendit_external_id', $externalId)->first();

        if (!$pembayaran) {
            Log::warning('CALLBACK_NOT_FOUND: Pembayaran tidak ditemukan di DB lokal.', ['external_id' => $externalId]);
            return response()->json(['message' => 'Transaction not found in local DB'], 200);
        }

        if ($pembayaran->status == 'success') {
            Log::info('CALLBACK_IGNORED: Transaksi sudah berstatus success sebelumnya.', ['pembayaran_id' => $pembayaran->id]);
            return response()->json(['message' => 'Already paid'], 200);
        }

        try {
            DB::beginTransaction();
            Log::info('CALLBACK_PROCESS: Transaction DB started.', ['pembayaran_id' => $pembayaran->id, 'incoming_status' => $status]);

            if ($status == 'PAID' || $status == 'SETTLED') {
                // Proses Sukses Bayar
                
                // 1. UPDATE TABEL PEMBAYARAN
                $pembayaran->update([
                    'status' => 'success',
                    'tanggal_bayar' => Carbon::parse($data['paid_at'] ?? now()),
                    'payment_channel' => $data['payment_method'] ?? 'XENDIT'
                ]);
                Log::info('CALLBACK_UPDATE: Status pembayaran diupdate ke SUCCESS.', ['pembayaran_id' => $pembayaran->id]);

                // 2. UPDATE TABEL PEMAKAIAN (PENTING!)
                $pemakaian = Pemakaian::find($pembayaran->id_pemakaian);
                
                if ($pemakaian) {
                    $pemakaian->update([
                        'status_pembayaran' => 'lunas',
                        'total_akhir' => $pembayaran->total_bayar, 
                        'tgl_bayar' => $pembayaran->tanggal_bayar
                    ]);
                    Log::info('CALLBACK_UPDATE: Status pemakaian diupdate ke LUNAS.', ['pemakaian_id' => $pemakaian->id]);
                } else {
                    Log::error("CALLBACK_ERROR: Data Pemakaian tidak ditemukan.", ['pembayaran_id' => $pembayaran->id]);
                }

            } else if ($status == 'EXPIRED') {
                // Proses Expired
                $pembayaran->update(['status' => 'expired']);
                Log::warning('CALLBACK_UPDATE: Status pembayaran diupdate ke EXPIRED.', ['pembayaran_id' => $pembayaran->id]);
                
                // Kembalikan status pemakaian jadi belum bayar
                $pemakaian = Pemakaian::find($pembayaran->id_pemakaian);
                if ($pemakaian) {
                    $pemakaian->update(['status_pembayaran' => 'belum_bayar']);
                    Log::info('CALLBACK_UPDATE: Status pemakaian dikembalikan ke BELUM_BAYAR.', ['pemakaian_id' => $pemakaian->id]);
                }
            }

            // LOGGING COMMIT: Cek apakah transaksi berhasil disimpan
            DB::commit();
            Log::info('CALLBACK_COMMIT: DB Transaction committed successfully.', ['external_id' => $externalId]);
            
            return response()->json(['message' => 'Success'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            // LOGGING ROLLBACK: Menangkap error fatal saat update data
            Log::critical('CALLBACK_FATAL_ERROR: Terjadi exception saat memproses callback.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
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

        // Log::debug jika diperlukan untuk debug kalkulasi detail (opsional, agar tidak spam)
        // Log::debug('CALCULATION: Detail perhitungan.', ['tagihan' => $tagihanPokok, 'denda' => $denda]);

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