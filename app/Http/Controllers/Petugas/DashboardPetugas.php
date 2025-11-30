<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Keluhan;
use App\Models\Informasi;
use Illuminate\Support\Str;

class DashboardPetugas extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $petugas = $user->petugas; 
        $id_company = $petugas->id_company ?? null;
        $nama_petugas = $petugas->nama ?? $user->username;
        $pemakaianBulanIni = 75; 
        $pemakaianBulanLalu = 68;
        $tagihanBulanIni = 185000;
        $jatuhTempo = Carbon::now()->addDays(10);
        $informasiAdmin = [];
        if ($id_company) {
            $informasiAdmin = Informasi::where('id_company', $id_company)
                ->orderBy('tanggal', 'desc')
                ->get();
        }
        $keluhanMasuk = [];
        
        if ($petugas) {
            $keluhanMasuk = Keluhan::where('id_petugas', $petugas->id) // <--- KUNCI PERUBAHANNYA
                            ->with('pelanggan') // Tetap load relasi pelanggan untuk menampilkan nama
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
        }
        return view('petugas.dashboard', compact(
            'pemakaianBulanIni',
            'pemakaianBulanLalu',
            'tagihanBulanIni',
            'jatuhTempo',
            'informasiAdmin',
            'keluhanMasuk', 
            'nama_petugas'
        ));
    }
}