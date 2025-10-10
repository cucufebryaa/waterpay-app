<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Petugas;    // Wajib diimpor
use App\Models\Pelanggan; // Wajib diimpor

class AdminController extends Controller
{
    public function index()
    {
        // 1. Dapatkan Company ID dari Admin yang sedang login (Multitenancy Logic)
        if (!Auth::check() || !Auth::user()->admin) {
            // Ini bisa terjadi jika data Admin tidak ada di tb_admins, padahal user role-nya admin.
            abort(403, 'Akses Ditolak: Data perusahaan Admin tidak ditemukan.');
        }
        
        // Ambil ID Perusahaan yang dikelola Admin
        $companyId = Auth::user()->admin->id_company; 
        
        // 2. Logika Perhitungan Metrik (MENGHITUNG DAN MENDEFINISIKAN SEMUA VARIABEL)
        
        // KRUSIAL: Menghitung $totalPetugas
        $totalPetugas = Petugas::where('id_company', $companyId)->count(); 
        
        // Menghitung variabel lain yang dibutuhkan View
        $totalPelanggan = Pelanggan::where('id_company', $companyId)->count(); 
        
        // Data dummy untuk Pembayaran dan Tunggakan (jika View menggunakannya)
        $pembayaranBulanIni = '5.500.000'; 
        $totalTunggakan = '1.500.000';     

        // 3. Mengirimkan SEMUA variabel yang diperlukan ke View
        return view('admin.dashboard', compact(
            'totalPetugas',           // <-- VARIABEL YANG HILANG SEKARANG DIKIRIMKAN
            'totalPelanggan',
            'pembayaranBulanIni',
            'totalTunggakan'
        ));
    }
}