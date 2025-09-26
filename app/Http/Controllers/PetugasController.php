<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Petugas; // <-- Pastikan model Petugas diimpor
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $petugas = Petugas::with(['user', 'company']);

        // Logika filter pencarian dari satu kolom
        if ($request->filled('search')) {
            $search = $request->search;
            $petugas->whereHas('user', function ($query) use ($search) {
                $query->where('username', 'like', "%{$search}%");
            })->orWhereHas('company', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        }
        
        $petugas = $petugas->get();

        return view('superadmin.data-petugas', compact('petugas'));
    }

    public function loginAs(Petugas $petugas)
    {
        Auth::login($petugas->user);
        return redirect()->route('petugas.dashboard');
    }
}