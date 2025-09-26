<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Petugas;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $admin = Admin::where('id_user', $user->id)->first();

        if (!$admin) {
            // Jika admin tidak ditemukan, tangani error
            abort(403, 'Data Admin tidak ditemukan.');
        }

        $companyId = $admin->id_company;

        $petugasCount = Petugas::where('id_company', $companyId)->count();
        $pelangganCount = Pelanggan::where('id_company', $companyId)->count();

        return view('admin.dashboard', compact('petugasCount', 'pelangganCount'));
    }
}