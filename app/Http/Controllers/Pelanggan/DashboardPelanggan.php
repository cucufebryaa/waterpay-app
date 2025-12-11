<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\Keluhan;
use App\Models\Informasi;
use App\Models\Pemakaian; // WAJIB
use App\Models\Pembayaran; // WAJIB
use App\Models\Pelanggan;   

class DashboardPelanggan extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Cek relasi Pelanggan
        if (!$user->pelanggan) {
            // Tentukan response jika pelanggan tidak terhubung
            return redirect()->route('login')->with('error', 'Data pelanggan tidak ditemukan.'); 
        }

        $pelanggan = $user->pelanggan;
        $id_pelanggan = $pelanggan->id;
        $id_company = $pelanggan->id_company ?? null; 
        $nama_pelanggan = $pelanggan->name ?? $user->username;
        $companyName = $user->pelanggan->perusahaan->nama_perusahaan ?? 'Perorangan'; 

        // --- 1. PENGAMBILAN DATA REAL-TIME ---
        
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Tentukan hari jatuh tempo secara statis (Misal: tanggal 20 setiap bulan)
        $hariJatuhTempoStatis = 10;

        // A. Ambil Pemakaian dan Tagihan Bulan INI (currentMonth)
        $recordBulanIni = Pemakaian::where('id_pelanggan', $id_pelanggan)
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->first();
            
        // B. Ambil Pemakaian Bulan LALU
        $recordBulanLalu = Pemakaian::where('id_pelanggan', $id_pelanggan)
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->first();

        // --- 2. HITUNG METRIK ---

        // Pemakaian Bulan Ini (m³)
        $pemakaianBulanIni = $recordBulanIni->total_pakai ?? 0;
        
        // Pemakaian Bulan Lalu (m³)
        $pemakaianBulanLalu = $recordBulanLalu->total_pakai ?? 0;
        
        // Tagihan Bulan Ini (Rupiah)
        $tagihanBulanIni = $recordBulanIni->total_tagihan ?? 0;

        $jatuhTempo = Carbon::now()->addDays(30);
        // --- 3. FORMATTING (untuk tampilan) ---
        $tagihanBulanIniFormatted = number_format($tagihanBulanIni, 0, ',', '.');
        $jatuhTempoDisplay = $jatuhTempo->translatedFormat('d M Y');

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