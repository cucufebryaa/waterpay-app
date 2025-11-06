<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Harga;
// Hapus atau abaikan ini jika Anda tidak membuatnya:
// use App\Models\Admin; 

class HargaController extends Controller
{
    /**
     * Helper privat untuk mendapatkan id_company dengan aman.
     */
    private function getCompanyId()
    {
        // Cek relasi 'adminData' dan apakah 'id_company' ada
        if (Auth::user() && Auth::user()->admin && Auth::user()->admin->id_company) {
            return Auth::user()->admin->id_company;
        }
        return null; // Kembalikan null jika tidak ditemukan
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_company = $this->getCompanyId();
        
        // Validasi: Pastikan user terhubung ke perusahaan
        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Akun Anda tidak terhubung dengan perusahaan manapun.');
        }

        $daftarHarga = Harga::where('id_company', $id_company)->get();
        return view('admin.harga.index', compact('daftarHarga'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('admin.harga.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_product'  => 'required|string|max:255',
            'tipe'          => 'required|in:tunggal,paket',
            'harga_product' => 'required|numeric|min:0',
            'biaya_admin'   => 'required|numeric|min:0',
            'denda'         => 'required|numeric|min:0',
        ]);

        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.harga.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        $kode = $this->generateKodeProduct($request->nama_product, $id_company);

        Harga::create([
            'id_company'    => $id_company, // <-- SEKARANG SUDAH BENAR
            'nama_product'  => $request->nama_product,
            'kode_product'  => $kode,
            'tipe'          => $request->tipe,
            'harga_product' => $request->harga_product,
            'biaya_admin'   => $request->biaya_admin,
            'denda'         => $request->denda,
        ]);

        return redirect()->route('admin.harga.index')->with('success', 'Data harga berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // (Biasanya tidak terpakai jika pakai modal)
        return redirect()->route('admin.harga.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // (Tidak terpakai karena kita pakai Modal Edit)
        return redirect()->route('admin.harga.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_product'  => 'required|string|max:255',
            'tipe'          => 'required|in:tunggal,paket',
            'harga_product' => 'required|numeric|min:0',
            'biaya_admin'   => 'required|numeric|min:0',
            'denda'         => 'required|numeric|min:0',
        ]);

        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.harga.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        $harga = Harga::where('id_company', $id_company)->findOrFail($id);
        $harga->update([
            'nama_product'  => $request->nama_product,
            'tipe'          => $request->tipe,
            'harga_product' => $request->harga_product,
            'biaya_admin'   => $request->biaya_admin,
            'denda'         => $request->denda,
        ]);

        return redirect()->route('admin.harga.index')->with('success', 'Data harga berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.harga.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        $harga = Harga::where('id_company', $id_company)->findOrFail($id);
        $harga->delete();

        return redirect()->route('admin.harga.index')->with('success', 'Data harga berhasil dihapus.');
    }

    /**
     * Generate Kode Product (Tidak berubah)
     */
    private function generateKodeProduct($nama_product, $id_company)
    {
        $prefix = strtoupper(substr($nama_product, 0, 2));
        $tanggal = date('d');
        // Kita hitung berdasarkan $id_company yang sudah benar
        $urutan = Harga::where('id_company', $id_company)->count() + 1;
        $urutanPadded = str_pad($urutan, 2, '0', STR_PAD_LEFT);
        $kode = $prefix . $urutanPadded . $tanggal;
        
        return $kode;
    }
}