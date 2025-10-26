<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; 
use App\Mail\RegistrationSuccessful;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'no_hp' => 'required|string|max:20',
            'nik' => 'nullable|string|max:16',
            'company_name' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:50',
            'no_rekening' => 'required|string|max:50',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // dd( $validated );

        // 2. Gunakan Transaksi Database
        DB::beginTransaction();

        try {
            // Langkah A: Buat entri di tabel 'users'
            $user = User::create([
                'email' => $validated['email'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => 'admin', // Role default adalah 'admin'
                'nik' => $validated['nik'] ?? null,
            ]);

            // Langkah B: Buat entri di tabel 'tb_companies'
            // (Saran perbaikan: Gunakan 'name' dari form, bukan 'username' untuk PJ)
            $company = Company::create([
                'nama_perusahaan' => $validated['company_name'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'nama_bank' => $validated['nama_bank'],
                'no_rekening' => $validated['no_rekening'],
                'username' => $validated['username'],
                'penanggung_jawab' => $validated['name'],
                'status' => 'pending', 
            ]);

            // Langkah C: Buat entri di tabel 'tb_admins'
            // (Saran perbaikan: Gunakan 'name' dari form, bukan 'username')
            Admin::create([
                'nama_lengkap' => $validated['name'],
                'username' => $validated['username'],
                'alamat' => $validated['alamat'],
                'email' => $user->email,
                'no_hp' => $validated['no_hp'],
                'id_user' => $user->id, 
                'id_company' => $company->id, 
            ]);

            // Jika semua berhasil, konfirmasi transaksi
            DB::commit();

        } catch (\Exception $e) {
            // Jika ada error DB, batalkan semua yang sudah disimpan
            DB::rollBack();

            // Redirect kembali dengan pesan error
            Log::error('REGISTRATION FAILED (DATABASE): ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi.');
        }
        
        // 3. Kirim Email Notifikasi
        try {
            $loginUrl = route('login'); // Mendapatkan URL ke halaman login
            
            // Mengirim email menggunakan Mailable yang sudah kita buat
            // Kita kirim $validated karena berisi semua data form (termasuk password plain-text)
            Mail::to($validated['email'])->send(new RegistrationSuccessful($validated, $loginUrl));

        } catch (\Exception $e) {
            // JIKA EMAIL GAGAL: Jangan batalkan registrasi. Cukup log error.
            // Registrasi pengguna tetap berhasil.
            Log::error('REGISTRATION SUCCEEDED (DB) BUT FAILED (EMAIL): ' . $e->getMessage());
        }

        // 4. Redirect ke halaman login dengan pesan sukses
        // (Pesan diubah sedikit untuk memberitahu user agar cek email)
        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan cek email Anda untuk detail akun. Akun Anda sedang ditinjau dan akan segera diaktifkan.');
    }
}