<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\Keluhan; // <<< Asumsi Anda memiliki Model Keluhan

class DashboardPelanggan extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data Metrik (Dummy)
        $pemakaianBulanIni = 75;
        $pemakaianBulanLalu = 68;
        $tagihanBulanIni = 185000;
        $jatuhTempo = Carbon::now()->addDays(10);
        
        // Data Pelanggan
        $nama_pelanggan = $user->pelanggan->name ?? $user->username;
        $id_pelanggan = $user->pelanggan->id;
        $company = $user->pelanggan->perusahaan->nama ?? 'Perorangan'; 

        $informasiAdmin = [
            [
                'id' => 1, 
                'judul' => 'Perbaikan Pipa Terjadwal', 
                'isi' => 'Akan ada penghentian sementara aliran air di area Anda pada tanggal 28 Nov 2025. Mohon persiapkan penampungan air.',
                'tanggal' => Carbon::parse('2025-11-15 10:00:00')
            ],
            [
                'id' => 2, 
                'judul' => 'Pengumuman Hari Raya', 
                'isi' => 'Layanan customer service akan libur pada tanggal 24-25 Desember 2025.',
                'tanggal' => Carbon::parse('2025-11-01 15:30:00')
            ]
        ];
        $keluhanPelanggan = Keluhan::where('id_pelanggan', $id_pelanggan)
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