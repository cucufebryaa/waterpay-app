<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\Keluhan;
use App\Models\Informasi;

class DashboardPelanggan extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pemakaianBulanIni = 75;
        $pemakaianBulanLalu = 68;
        $tagihanBulanIni = 185000;
        $jatuhTempo = Carbon::now()->addDays(10);
        $id_company = $user->pelanggan->id_company ?? null; 
        $nama_pelanggan = $user->pelanggan->name ?? $user->username;
        $id_pelanggan = $user->pelanggan->id;
        $company = $user->pelanggan->perusahaan->nama ?? 'Perorangan'; 
        $informasiAdmin = [];
        
        if ($id_company) {
            $informasiAdmin = Informasi::where('id_company', $id_company)
                ->orderBy('tanggal', 'desc') // Urutkan dari yang paling baru
                ->get();
        }

        $keluhanPelanggan = Keluhan::where('id_pelanggan', $id_pelanggan)
                            ->with('maintenance') 
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();

        return view('pelanggan.dashboard', compact(
            'pemakaianBulanIni',
            'pemakaianBulanLalu',
            'tagihanBulanIni',
            'jatuhTempo',
            'informasiAdmin',
            'keluhanPelanggan',
            'nama_pelanggan',
            'company'
        ));
    }
}