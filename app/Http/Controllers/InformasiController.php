<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Informasi;

class InformasiController extends Controller
{
    /**
     * Helper privat untuk mendapatkan id_company dengan aman.
     * Ini disalin dari HargaController Anda.
     */
    private function getCompanyId()
    {
        if (Auth::user() && Auth::user()->admin && Auth::user()->admin->id_company) {
            return Auth::user()->admin->id_company;
        }
        return null;
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

        // Mengambil data informasi berdasarkan id_company
        $daftarInformasi = Informasi::where('id_company', $id_company)->get();
        
        // Mengarahkan ke view yang sesuai
        return view('admin.informasi.index', compact('daftarInformasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect ke index, asumsi menggunakan modal
        return redirect()->route('admin.informasi.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pesan'   => 'required|string',
        ]);

        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.informasi.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        // Membuat data baru
        Informasi::create([
            'id_company' => $id_company, // <-- Wajib ada
            'tanggal'    => $request->tanggal,
            'pesan'      => $request->pesan,
        ]);

        return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Redirect ke index, asumsi menggunakan modal
        return redirect()->route('admin.informasi.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Redirect ke index, asumsi menggunakan modal
        return redirect()->route('admin.informasi.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pesan'   => 'required|string',
        ]);

        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.informasi.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        // Cari informasi, pastikan milik company yg login
        $informasi = Informasi::where('id_company', $id_company)->findOrFail($id);
        
        $informasi->update([
            'tanggal' => $request->tanggal,
            'pesan'   => $request->pesan,
        ]);

        return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->route('admin.informasi.index')->with('error', 'Gagal: Akun Anda tidak terhubung dengan perusahaan.');
        }

        // Cari informasi, pastikan milik company yg login
        $informasi = Informasi::where('id_company', $id_company)->findOrFail($id);
        $informasi->delete();

        return redirect()->route('admin.informasi.index')->with('success', 'Informasi berhasil dihapus.');
    }
}
