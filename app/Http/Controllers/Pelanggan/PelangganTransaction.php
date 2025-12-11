<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use App\Models\Keluhan;
use App\Models\Pemakaian; // WAJIB
use App\Models\Pembayaran; // WAJIB
//use \Carbon\Carbon
//use Carbon\CarbonPeriod;

class PelangganTransaction extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->role == "pelanggan") {
             abort(403, 'Data Admin Perusahaan tidak ditemukan.');
        }

        // ambil company id
        $companyId = Auth::user()->pelanggan->id_company;
        $nama_pelanggan = Auth::user()->pelanggan->nama;
        $perusahaan = Company::where('id', $companyId)->first();

        // data dummy untuk dashboard
        $data = [
            'pemakaianBulanIni'  => 15, // dalam m³
            'pemakaianBulanLalu' => 12, // dalam m³
            'tagihanBulanIni'    => 75000, // dalam Rupiah
            'jatuhTempo'         => \Carbon\Carbon::now()->addDays(5), // 5 hari dari sekarang
            'company'            => $perusahaan -> nama_perusahaan,
            'nama_pelanggan'     => $nama_pelanggan,
        ];

        $data['informasiAdmin'] = [
            [
                'id' => 1,
                'judul' => 'Perbaikan Pipa di Area Mawar',
                'isi' => 'Akan ada pemadaman air terjadwal pada tanggal 20 November 2025 di area Mawar... (klik untuk detail)',
                'tanggal' => \Carbon\Carbon::parse('2025-11-15')
            ],
            [
                'id' => 2,
                'judul' => 'Promo Pembayaran Digital',
                'isi' => 'Dapatkan cashback 10% untuk pembayaran melalui e-wallet... (klik untuk detail)',
                'tanggal' => \Carbon\Carbon::parse('2025-11-10')
            ]
        ];

        return view('pelanggan.dashboard', $data);
    }

    public function create()
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan; 
        $companyId = $pelanggan->id_company;
        $company_name = Company::where('id', $companyId)->first();

        $data = [   
            'user' => $user,
            'pelanggan' => $pelanggan,
            // Ambil kolom spesifik (misal: nama)
            'company_name' => $company_name ? $company_name->nama_perusahaan : 'Nama Tidak Ditemukan'
        ];
        

        return view('pelanggan.keluhan', $data );
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        // Kita hanya perlu memvalidasi isi keluhan, karena data lain diambil dari sistem
        $request->validate([
            'isi_keluhan' => 'required|string|min:10',
        ], [
            'isi_keluhan.required' => 'Detail keluhan wajib diisi.',
            'isi_keluhan.min' => 'Jelaskan keluhan Anda minimal 10 karakter agar kami paham.',
        ]);

        // 2. Ambil Data User yang Sedang Login
        $user = Auth::user();
        
        // Pastikan User memiliki relasi ke tabel Pelanggan
        // Asumsi: Di model User ada function pelanggan() { return $this->hasOne(Pelanggan::class); }
        $pelanggan = $user->pelanggan;

        if (!$pelanggan) {
            return redirect()->back()->with('error', 'Data profil pelanggan tidak ditemukan. Silakan lengkapi profil.');
        }

        // 3. Simpan ke Database
        Keluhan::create([
            'tanggal'      => \Carbon\Carbon::now(),          // Otomatis tanggal & jam sekarang
            'keluhan'      => $request->isi_keluhan,  // Dari input textarea
            'status'       => 'open',                 // DEFAULT STATUS: OPEN
            'id_pelanggan' => $pelanggan->id,         // Ambil ID Pelanggan dari Auth
            'id_company'   => $pelanggan->id_company, // Ambil ID Company dari data Pelanggan
            'id_petugas'   => null,                   // Default null karena belum ada petugas yang handle
        ]);

        // 4. Redirect dengan Pesan Sukses
        // Sesuaikan route redirect-nya, misal ke halaman riwayat keluhan atau dashboard
        return redirect()->route('pelanggan.dashboard')->with('success', 'Keluhan Anda berhasil dikirim dan statusnya kini Open.');
    }
    public function edit()
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        
        // Ambil data pelanggan terkait (asumsi relasi 'pelanggan' sudah ada di model User)
        // Jika relasi belum ada, pastikan di Model User ada: public function pelanggan() { return $this->hasOne(Pelanggan::class, 'id_user'); }
        $pelanggan = $user->pelanggan;

        return view('pelanggan.profile', compact('user', 'pelanggan'));
    }

    // 2. Memproses Update Data
    public function update(Request $request)
    {
        $user = Auth::user();
        $pelanggan = $user->pelanggan;

        // Validasi Input
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id, // Cek unik kecuali punya sendiri
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_hp'    => 'required|numeric',
            'alamat'   => 'required|string',
            'password' => 'nullable|string|min:6|confirmed', // Password opsional
        ]);

        // Update Tabel Users (Akun)
        $userData = [
            'username' => $request->username,
            'email'    => $request->email,
        ];

        // Cek jika user mengisi password baru
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update Tabel Pelanggans (Data Diri)
        // Pastikan field ini ada di $fillable model Pelanggan
        $pelanggan->update([
            'nama'   => $request->name,
            'no_hp'  => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('pelanggan.profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}