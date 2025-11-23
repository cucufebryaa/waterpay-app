<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Keluhan;
use App\Models\Maintenance;


class taskMaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $petugas = $user->petugas;
        $tasks = Keluhan::where('id_petugas', $petugas->id)
                        ->whereIn('status', ['delegated', 'onprogress'])
                        ->with('pelanggan') // Load data pelanggan
                        ->orderBy('created_at', 'asc') // Yang lama dikerjakan dulu
                        ->get();

        return view('petugas.index', compact('tasks'));
    }

    public function startProgress($id)
    {
        $keluhan = Keluhan::findOrFail($id);

        // Validasi: Pastikan statusnya masih Open
        if ($keluhan->status == 'delegated') {
            $keluhan->update(['status' => 'onprogress']);
            return redirect()->back()->with('success', 'Status pekerjaan diubah menjadi On Progress.');
        }

        return redirect()->back()->with('error', 'Pekerjaan tidak dapat dimulai (Status tidak valid).');
    }

    /**
     * Show the form for creating a new resource.
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
            'keluhan_id' => 'required|exists:tb_keluhans,id',
            'foto'       => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'  => 'required|string',
        ]);

        // 1. Upload Foto
        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('maintenance_photos', 'public');
        }

        // 2. Simpan ke tb_maintenances
        Maintenance::create([
            'keluhan_id' => $request->keluhan_id,
            'foto'       => $pathFoto,
            'deskripsi'  => $request->deskripsi,
            'tanggal'    => Carbon::now(),
        ]);

        // 3. Update Status Keluhan jadi Completed
        $keluhan = Keluhan::findOrFail($request->keluhan_id);
        $keluhan->update(['status' => 'Completed']);

        return redirect()->route('petugas.maintenance.index')->with('success', 'Laporan pekerjaan berhasil disubmit & tugas selesai!');
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
