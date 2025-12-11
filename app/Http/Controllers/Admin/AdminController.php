<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Petugas;    // Wajib diimpor
use App\Models\Pelanggan; // Wajib diimpor
use App\Models\Pembayaran; // WAJIB DIIMPOR
use App\Models\Pemakaian;  // WAJIB DIIMPOR
use Carbon\Carbon;         // WAJIB DIIMPOR
use Illuminate\Support\Collection;

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

        // --- Persiapan Data Grafik (12 Bulan Terakhir) ---
        
        // Menggunakan notasi lengkap untuk Collection untuk menghindari error syntax
        $months = new \Illuminate\Support\Collection();   
        $pembayaranBulanIni = new \Illuminate\Support\Collection();    
        $totalTunggakan = new \Illuminate\Support\Collection(); 
        
        // Looping mundur 12 bulan (dari bulan ini hingga 11 bulan sebelumnya)
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            // 1. Label Bulan
            $months->push($date->translatedFormat('M Y'));
        }
        
        // 2. Logika Perhitungan Metrik (MENGHITUNG DAN MENDEFINISIKAN SEMUA VARIABEL)
        // Tentukan periode bulan saat ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // KRUSIAL: Menghitung $totalPetugas
        $totalPetugas = Petugas::where('id_company', $companyId)->count(); 
        
        // Menghitung variabel lain yang dibutuhkan View
        $totalPelanggan = Pelanggan::where('id_company', $companyId)->count(); 
        
// A. Total Pembayaran Bulan Ini (Sudah LUNAS)
        // Hitung total_bayar dari tabel 'tb_pembayaran' di bulan ini dengan status 'success'
        $totalPembayaranBulanIni = Pembayaran::where('id_company', $companyId)
                ->where('Status', 'Success') // Field 'Status' di Tabel Pembayaran
                ->whereYear('Tanggal_Bayar', $date->year) // Field 'Tanggal_Bayar' di Tabel Pembayaran
                ->whereMonth('Tanggal_Bayar', $date->month)
                ->sum('Total_Bayar'); // Field 'Total_Bayar' di Tabel Pembayaran
        $months->push($date->translatedFormat('M Y'));

// B. Total Tunggakan (Belum Lunas / Pending)
        // Hitung total_tagihan dari tabel 'tb_pemakaian' dengan status 'belum_bayar'
        $totalTunggakan = Pemakaian::where('id_company', $companyId)
                ->where('Status_pembayaran', '!=', 'LUNAS') 
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('Total_Tagihan'); 
        $months->push($date->translatedFormat('M Y'));
            
        // 3. Formatting ke format Rupiah
        $pembayaranBulanIni = number_format($totalPembayaranBulanIni, 0, ',', '.') . ',-';
        $totalTunggakan = number_format($totalTunggakan, 0, ',', '.') . ',-';

        // 4. Mengirimkan SEMUA variabel yang diperlukan ke View
        return view('admin.dashboard', compact(
            'totalPetugas',           // <-- VARIABEL YANG HILANG SEKARANG DIKIRIMKAN
            'totalPelanggan',
            'months',
            'pembayaranBulanIni',
            'totalTunggakan'
        ));
    }
}