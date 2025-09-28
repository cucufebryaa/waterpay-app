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
            $company = Company::create([
                'name' => $validated['company_name'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'nama_bank' => $validated['nama_bank'],
                'no_rekening' => $validated['no_rekening'],
                'penanggung_jawab' => $user->username, // Mengambil nama PJ dari user yang baru dibuat
                'status' => 'pending', // Status awal, menunggu approval superadmin
            ]);

            // Langkah C: Buat entri di tabel 'tb_admins'
            Admin::create([
                'name' => $user->username,
                'alamat' => $validated['alamat'],
                'email' => $user->email,
                'no_hp' => $validated['no_hp'],
                'id_user' => $user->id, // Menghubungkan ke ID user yang baru dibuat
                'id_company' => $company->id, // Menghubungkan ke ID company yang baru dibuat
            ]);

            // Jika semua berhasil, konfirmasi transaksi
            DB::commit();

            // 3. Redirect ke halaman login dengan pesan sukses
            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Akun Anda sedang ditinjau dan akan segera diaktifkan oleh Super Admin.');

        } catch (\Exception $e) {
            // Jika ada error, batalkan semua yang sudah disimpan
            DB::rollBack();

            // Redirect kembali dengan pesan error
            Log::error('REGISTRATION FAILED: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat pendaftaran. Silakan coba lagi.');
        }

    }
}