<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keluhan;
use App\Models\Petugas;
use Illuminate\Validation\Rule;


class KeluhanController extends Controller
{

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

        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Akun Anda tidak terhubung dengan perusahaan manapun.');
        }

        $daftarKeluhan = Keluhan::with(['pelanggan', 'petugas', 'maintenance'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        
        $daftarPetugas = Petugas::where('id_company', $id_company)->get();

        return view('admin.keluhan.index', compact('daftarKeluhan', 'daftarPetugas'));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        // Validasi input dari modal delegasi
        $request->validate([
            'id_petugas' => [
                'required',
                // Pastikan id_petugas yang dikirim ada di database
                // dan milik perusahaan yang sama
                Rule::exists('tb_petugas', 'id')->where(function ($query) use ($id_company) {
                    $query->where('id_company', $id_company);
                }),
            ],
            'status' => 'required|string|in:Open,Delegated,OnProgress,Completed,Rejected',
        ]);

        // Cari keluhan yang spesifik, pastikan milik perusahaan ini
        $keluhan = Keluhan::where('id', $id)
                        ->where('id_company', $id_company)
                        ->firstOrFail();

        // Update data keluhan
        $keluhan->id_petugas = $request->id_petugas;
        $keluhan->status = $request->status;
        $keluhan->save();

        return redirect()->route('admin.keluhan.index')->with('success', 'Keluhan #' . $keluhan->id . ' berhasil didelegasikan/diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id_company = $this->getCompanyId();
        if (is_null($id_company)) {
            return redirect()->back()->with('error', 'Aksi tidak diizinkan.');
        }

        $keluhan = Keluhan::where('id', $id)
                        ->where('id_company', $id_company)
                        ->firstOrFail();
        
        $keluhan->delete();

        return redirect()->route('admin.keluhan.index')->with('success', 'Keluhan #' . $keluhan->id . ' berhasil dihapus.');
    }
}
