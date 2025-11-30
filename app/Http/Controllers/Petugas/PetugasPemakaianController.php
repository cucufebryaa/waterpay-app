<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Pemakaian;
use App\Models\Harga;
use Illuminate\Support\Str;


class PetugasPemakaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $petugas = $user->petugas; 
        if (!$petugas) {
            return back()->with('error', 'Data profil petugas tidak ditemukan. Hubungi Admin.');
        }

        $idCompany = $petugas->id_company; 
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $pelangganPending = Pelanggan::where('id_company', $idCompany)
            ->whereDoesntHave('pemakaian', function($query) use ($bulanIni, $tahunIni) {
                $query->whereMonth('created_at', $bulanIni)
                    ->whereYear('created_at', $tahunIni);
            })
            ->with('kode_product')
            ->get();
        foreach ($pelangganPending as $p) {
            $lastUsage = Pemakaian::where('id_pelanggan', $p->id)
                ->latest()
                ->first();
            
            $p->stand_meter_terakhir = $lastUsage ? $lastUsage->meter_akhir : 0;
        }
        $riwayatPencatatan = Pemakaian::with(['pelanggan', 'pelanggan.kode_product']) // Load relasi pelanggan & produk
            ->where('id_petugas', $petugas->id) // Filter: Hanya milik petugas yang login
            ->whereYear('created_at', $tahunIni)
            ->latest()
            ->get();
        return view('petugas.pemakaian.index', compact('pelangganPending', 'riwayatPencatatan'));
    }

    /**
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:tb_pelanggans,id',
            'meter_awal'   => 'required|numeric',
            'meter_akhir'  => 'required|numeric|gte:meter_awal',
            'image_base64' => 'required', 
        ]);

        try {
            $user = Auth::user();
            $petugas = $user->petugas;
            
            // --- 1. PROSES GAMBAR (Tetap sama) ---
            $base64_image = $request->input('image_base64');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                $data = substr($base64_image, strpos($base64_image, ',') + 1);
                $type = strtolower($type[1]); 

                if (!in_array($type, [ 'jpg', 'jpeg', 'png' ])) {
                    throw new \Exception('Tipe file gambar tidak valid');
                }
                $data = base64_decode($data);
                if ($data === false) {
                    throw new \Exception('Gagal decode base64 gambar');
                }
            } else {
                throw new \Exception('Data gambar tidak valid');
            }

            $fileName = 'bukti-meteran/' . Str::random(20) . '.' . $type;
            Storage::disk('public')->put($fileName, $data);

            // --- 2. LOGIKA UTAMA (PERBAIKAN VIA ID_PRODUCT) ---
            
            // A. Ambil Data Pelanggan
            $pelangganDb = Pelanggan::findOrFail($request->id_pelanggan);

            // B. Cek Foreign Key id_product
            if (empty($pelangganDb->id_product)) {
                throw new \Exception("Data Pelanggan '{$pelangganDb->nama}' belum disetting Produk/Tarif-nya (id_product kosong). Mohon edit data pelanggan terlebih dahulu.");
            }

            // C. Ambil Data Harga Master LANGSUNG berdasarkan Primary Key (Sangat Cepat & Akurat)
            $dataHarga = Harga::find($pelangganDb->id_product);

            if (!$dataHarga) {
                throw new \Exception("Data Harga dengan ID '{$pelangganDb->id_product}' tidak ditemukan di database master. Hubungi Admin.");
            }

            // D. Hitung Tagihan
            $meterAwal   = $request->meter_awal;
            $meterAkhir  = $request->meter_akhir;
            $totalPakai  = $meterAkhir - $meterAwal;
            
            $hargaPerM3  = $dataHarga->harga_product;
            $biayaAdmin  = $dataHarga->biaya_admin;
            // Ambil kode stringnya juga untuk disimpan sebagai snapshot history
            $kodeString  = $dataHarga->kode_product; 

            // Rumus: (Pemakaian x Harga) + Admin
            $subTotal    = $totalPakai * $hargaPerM3;
            $totalTagihan = $subTotal + $biayaAdmin;

            // --- 3. SIMPAN KE DATABASE ---
            $pemakaian = new Pemakaian();
            $pemakaian->id_pelanggan = $request->id_pelanggan;
            $pemakaian->id_petugas   = $petugas->id;
            $pemakaian->id_company   = $petugas->id_company;
            $pemakaian->meter_awal   = $meterAwal;
            $pemakaian->meter_akhir  = $meterAkhir;
            $pemakaian->total_pakai  = $totalPakai;
            
            // Simpan snapshot harga & produk yang dipakai saat ini
            $pemakaian->kd_product    = $kodeString; // Tetap simpan kodenya (misal: "RT1") untuk display report
            $pemakaian->tarif         = $hargaPerM3; 
            // $pemakaian->biaya_admin = $biayaAdmin; // Uncomment jika kolom tersedia
            $pemakaian->total_tagihan = $totalTagihan; 
            
            $pemakaian->foto          = $fileName;
            
            $pemakaian->save();

            return redirect()->route('petugas.pemakaian.index')
                ->with('success', 'Berhasil! Tagihan: Rp ' . number_format($totalTagihan, 0, ',', '.') . ' (Pakai: '.$totalPakai.' m³)');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
