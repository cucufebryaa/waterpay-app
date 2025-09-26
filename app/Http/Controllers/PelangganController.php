<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = Pelanggan::with(['user', 'company']);

        if ($request->filled('search')) {
            $search = $request->search;
            $pelanggan->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%{$search}%");
            })->orWhereHas('company', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }
        
        $pelanggan = $pelanggan->get();

        return view('superadmin.data-pelanggan', compact('pelanggan'));
    }

    public function loginAs(Pelanggan $pelanggan)
    {
        Auth::login($pelanggan->user);
        return redirect()->route('pelanggan.dashboard');
    }
}