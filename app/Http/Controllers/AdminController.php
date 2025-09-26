<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $admins = Admin::with(['user', 'company']);

        if ($request->filled('search')) {
            $search = $request->search;
            $admins->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('company', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }
        
        $admins = $admins->get();

        return view('superadmin.data-admin', compact('admins'));
    }

    public function create()
    {
        // ... Logika untuk form tambah admin
    }

    public function store(Request $request)
    {
        // ... Logika untuk menyimpan data admin
    }

    public function edit(Admin $admin)
    {
        // ... Logika untuk menampilkan form edit admin
    }

    public function update(Request $request, Admin $admin)
    {
        // ... Logika untuk mengupdate data admin
    }

    public function destroy(Admin $admin)
    {
        // ... Logika untuk menghapus data admin
    }
    
    public function loginAs(Admin $admin)
    {
        Auth::login($admin->user);
        return redirect()->route('admin.dashboard');
    }
}