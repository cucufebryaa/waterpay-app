<?php

namespace App\Http\Controllers\Admin; // Namespace harus App\Http\Controllers\Admin

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Petugas; // Wajib diimpor
use App\Models\User;    // Wajib diimpor (untuk relasi)
// Pastikan model Admin juga diimpor jika diperlukan

class PetugasController extends Controller
{
    /**
     * Menampilkan daftar Petugas yang terkait dengan perusahaan Admin yang sedang login.
     * Logika ini menggantikan logika yang eror sebelumnya.
     */
    public function index(Request $request)
    {
        // 1. Dapatkan Company ID dari Admin yang sedang login (Logika Multitenancy)
        // Jika Auth::user()->admin tidak ditemukan (misalnya, error pada relasi), maka kita tangani
        if (!Auth::check() || !Auth::user()->admin) {
             // Ini seharusnya ditangani oleh middleware, tapi sebagai fail-safe
             abort(403, 'Data Admin Perusahaan tidak ditemukan.');
        }
        
        // Ambil ID Perusahaan yang dikelola oleh Admin yang login
        // ASUMSI: Model User berelasi ke Model Admin, dan Model Admin punya kolom id_company
        $companyId = Auth::user()->admin->id_company; 
        $search = $request->get('search');
        
        // 2. Query Dasar: Ambil Petugas berdasarkan Company ID
        $petugasQuery = Petugas::where('id_company', $companyId)
                               ->with('user'); // Wajib: Ambil data user terkait untuk username, dll.

        // 3. Logika Pencarian (Filter berdasarkan Username atau Nama)
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $petugasQuery->where(function($query) use ($searchTerm) {
                // Cari di kolom 'name' petugas
                $query->where('name', 'like', $searchTerm)
                      // Cari di kolom 'username' melalui relasi 'user'
                      ->orWhereHas('user', function ($subQuery) use ($searchTerm) {
                          $subQuery->where('username', 'like', $searchTerm);
                      });
            });
        }
        
        // 4. Ambil data (gunakan paginate jika data banyak)
        $petugas = $petugasQuery->latest()->get(); 
        
        // 5. Tampilkan View
        // Mengirimkan variabel $petugas ke view
        return view('admin.index', compact('petugas', 'search'));
    }
}