<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemakaian; // <-- Gunakan Model Pemakaian
use Illuminate\Support\Facades\Storage; // <-- Tambahkan untuk handle Foto
use Illuminate\Support\Str; // <-- Tambahkan untuk generate nama file

class PemakaianController extends Controller
{
    /**
     * Helper untuk mendapatkan id_company admin.
     * (NOTE: Sebaiknya ini diletakkan di BaseController agar tidak duplikat)
     */
    private function getCompanyId()
    {
        // Diubah dari adminData -> admin agar sesuai flow Anda
        if (Auth::user() && Auth::user()->admin && Auth::user()->admin->id_company) {
            return Auth::user()->admin->id_company;
        }
        return null;
    }

    /**
     * Tampilkan halaman utama Riwayat Pemakaian.
     */
    public function index()
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Akun Anda tidak terhubung dengan perusahaan manapun.');
        }
        $daftarRiwayat = Pemakaian::where('id_company', $id_company)
                            ->with(['pelanggan', 'petugas', 'pembayaran', 'kode_product']) // Ambil semua relasi
                            ->orderBy('created_at', 'desc') // Tampilkan yang terbaru di atas
                            ->paginate(20); // Gunakan paginate untuk data yang banyak

        return view('admin.pemakaian.index', compact('daftarRiwayat'));
    }

    /**
     * Show the form for creating a new resource.
     * (Baru)
     */
    public function create()
    {
        // Sesuai flow modal, redirect ke index
        return redirect()->route('admin.pemakaian.index');
    }

    /**
     * Store a newly created resource in storage.
     * (Baru - Disesuaikan untuk Pemakaian)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:tb_pelanggans,id', // Pastikan nama tabel pelanggan benar
            'id_petugas'   => 'required|exists:tb_petugas,id', // Pastikan nama tabel petugas benar
            'meter_awal'   => 'required|numeric|min:0',
            'meter_akhir'  => 'required|numeric|gt:meter_awal', // meter akhir > meter awal
            'foto'         => 'required|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
            'kd_product'   => 'required|exists:tb_harga,kode_product', // Pastikan nama tabel harga benar
            'tarif'        => 'required|numeric|min:0',
        ]);

        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.pemakaian.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        // 1. Handle File Upload
        $path = null;
        if ($request->hasFile('foto')) {
            $namaFile = Str::uuid() . '.' . $request->file('foto')->extension();
            // Simpan di folder 'public/uploads/meteran'
            $path = $request->file('foto')->storeAs('uploads/meteran', $namaFile, 'public');
        }

        // 2. Hitung Total Pakai
        $total_pakai = $request->meter_akhir - $request->meter_awal;

        // 3. Simpan data
        Pemakaian::create([
            'id_company'   => $id_company,
            'id_pelanggan' => $request->id_pelanggan,
            'id_petugas'   => $request->id_petugas,
            'meter_awal'   => $request->meter_awal,
            'meter_akhir'  => $request->meter_akhir,
            'foto'         => $path,
            'total_pakai'  => $total_pakai,
            'kd_product'   => $request->kd_product,
            'tarif'        => $request->tarif,
        ]);

        return redirect()->route('admin.pemakaian.index')->with('success', 'Data pemakaian berhasil ditambahkan.');
    }

    /**
     * (Opsional) Tampilkan detail via AJAX/Fetch jika diperlukan.
     * Untuk saat ini, kita kirim semua data ke view.
     */
    public function show($id)
    {
        // Sesuai flow modal, redirect ke index
        return redirect()->route('admin.pemakaian.index');
    }

    /**
     * Show the form for editing the specified resource.
     * (Baru)
     */
    public function edit(string $id)
    {
        // Sesuai flow modal, redirect ke index
        return redirect()->route('admin.pemakaian.index');
    }

    /**
     * Update the specified resource in storage.
     * (Baru - Disesuaikan untuk Pemakaian)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:tb_pelanggans,id',
            'id_petugas'   => 'required|exists:tb_petugas,id',
            'meter_awal'   => 'required|numeric|min:0',
            'meter_akhir'  => 'required|numeric|gt:meter_awal',
            'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Foto opsional saat update
            'kd_product'   => 'required|exists:tb_harga,kode_product',
            'tarif'        => 'required|numeric|min:0',
        ]);

        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.pemakaian.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        // Cari data pemakaian
        $pemakaian = Pemakaian::where('id_company', $id_company)->findOrFail($id);

        // 1. Handle File Upload (jika ada file baru)
        $path = $pemakaian->foto; // Ambil path foto lama by default
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($pemakaian->foto && Storage::disk('public')->exists($pemakaian->foto)) {
                Storage::disk('public')->delete($pemakaian->foto);
            }
            // Simpan foto baru
            $namaFile = Str::uuid() . '.' . $request->file('foto')->extension();
            $path = $request->file('foto')->storeAs('uploads/meteran', $namaFile, 'public');
        }

        // 2. Hitung Ulang Total Pakai
        $total_pakai = $request->meter_akhir - $request->meter_awal;

        // 3. Update data
        $pemakaian->update([
            'id_pelanggan' => $request->id_pelanggan,
            'id_petugas'   => $request->id_petugas,
            'meter_awal'   => $request->meter_awal,
            'meter_akhir'  => $request->meter_akhir,
            'foto'         => $path,
            'total_pakai'  => $total_pakai,
            'kd_product'   => $request->kd_product,
            'tarif'        => $request->tarif,
        ]);

        return redirect()->route('admin.pemakaian.index')->with('success', 'Data pemakaian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     * (Baru - Disesuaikan untuk Pemakaian)
     */
    public function destroy(string $id)
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.pemakaian.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        // Cari data pemakaian
        $pemakaian = Pemakaian::where('id_company', $id_company)->findOrFail($id);

        // Hapus foto dari storage
        if ($pemakaian->foto && Storage::disk('public')->exists($pemakaian->foto)) {
            Storage::disk('public')->delete($pemakaian->foto);
        }

        // Hapus data dari database
        $pemakaian->delete();

        return redirect()->route('admin.pemakaian.index')->with('success', 'Data pemakaian berhasil dihapus.');
    }
}
