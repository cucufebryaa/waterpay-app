<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterAdminController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register-admin');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'penanggung_jawab' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // 1. Buat entri di tabel tb_companies
        $company = Company::create([
            'name' => $request->company_name,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'nama_bank' => $request->nama_bank,
            'no_rekening' => $request->no_rekening,
            'pj' => $request->penanggung_jawab,
            'status' => 'pending', // Status awal 'pending'
        ]);

        // 2. Buat entri di tabel users dengan role 'admin'
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);
        
        // 3. Buat entri di tabel tb_admins
        Admin::create([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'id_user' => $user->id,
            'id_company' => $company->id,
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('success', 'Pendaftaran berhasil! Akun Anda menunggu persetujuan Super Admin.');
    }
}