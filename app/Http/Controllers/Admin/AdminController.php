<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Dapatkan ID perusahaan dari admin yang sedang login
        // Ini adalah langkah paling penting untuk memfilter data
        $companyId = Auth::user()->admin->id_company;

        // 2. Ambil data yang relevan HANYA untuk company tersebut
        // (Ganti query ini dengan query ke database Anda yang sebenarnya)

        // Contoh: Mengambil jumlah pelanggan dari perusahaan ini
        // $jumlahPelanggan = Pelanggan::where('id_company', $companyId)->count();
        $jumlahPelanggan = 120; // Data dummy

        // Contoh: Mengambil jumlah petugas dari perusahaan ini
        // $jumlahPetugas = User::where('role', 'petugas')->where('id_company', $companyId)->count();
        $jumlahPetugas = 5; // Data dummy

        // Contoh: Mengambil total tagihan yang belum dibayar
        // $totalTunggakan = Tagihan::where('id_company', $companyId)->where('status', 'belum_bayar')->sum('total');
        $totalTunggakan = 1500000; // Data dummy

        // Contoh: Mengambil total pembayaran bulan ini
        // $pembayaranBulanIni = Pembayaran::where('id_company', $companyId)->whereMonth('created_at', now()->month)->sum('jumlah_bayar');
        $pembayaranBulanIni = 5500000; // Data dummy

        // 3. Kirim data ke view
        return view('admin.dashboard', compact(
            'jumlahPelanggan',
            'jumlahPetugas',
            'totalTunggakan',
            'pembayaranBulanIni'
        ));
    }
}
