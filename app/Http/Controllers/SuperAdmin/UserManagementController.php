<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Model utama yang menyimpan username dan role
use App\Models\Admin;
use App\Models\Petugas;
use App\Models\Pelanggan;

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar semua user (Admin, Petugas, Pelanggan) dengan filter.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Mengambil SEMUA user (Admin, Petugas, Pelanggan)
        $usersQuery = User::whereIn('role', ['admin', 'petugas', 'pelanggan'])
            ->with(['admin', 'petugas', 'pelanggan']); // Eager load relasi polymorph (jika ada)

        // Logika Filter/Search berdasarkan username
        if ($search) {
            $usersQuery->where('username', 'like', '%' . $search . '%');
        }
        
        $users = $usersQuery->latest()->get(); 
        
        // Mempersiapkan data untuk tabel (menggabungkan data dari relasi)
        $userData = $users->map(function ($user) {
            $relatedModel = $user->admin ?? $user->petugas ?? $user->pelanggan;
            $companyName = 'N/A';
            $alamat = 'N/A';

            // Mendapatkan Company Name dan Alamat dari relasi terkait
            if ($relatedModel && property_exists($relatedModel, 'company') && $relatedModel->company) {
                $companyName = $relatedModel->company->name;
                $alamat = $relatedModel->alamat;
            }
            
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => ucfirst($user->role), // Admin, Petugas, Pelanggan
                'nama_lengkap' => $relatedModel->name ?? 'N/A',
                'alamat' => $alamat,
                'nama_perusahaan' => $companyName,
            ];
        });

        // Tampilan diarahkan ke halaman user_management_index
        return view('superadmin.users.user_management_index', compact('userData', 'search'));
    }
}