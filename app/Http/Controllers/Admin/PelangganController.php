<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Validation\Rule;
class PelangganController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || !Auth::user()->admin) {
             abort(403, 'Data Admin Perusahaan tidak ditemukan.');
        }
        $companyId = Auth::user()->admin->id_company; 
        $search = $request->get('search');
        $pelangganQuery = Pelanggan::where('id_company', $companyId)
                               ->with('user');
        if ($search) {
            $searchTerm = '%' . $search . '%';
            $pelangganQuery->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                      ->orWhereHas('user', function ($subQuery) use ($searchTerm) {
                          $subQuery->where('username', 'like', $searchTerm);
                      });
            });
        }
        $pelanggan = $pelangganQuery->latest()->get(); 
        return view('admin.kelola-pengguna', compact('pelanggan', 'search'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        // ===================== PERBARUI VALIDASI =====================
        $request->validate([
            'nama' => 'required|string|max:255', // Diubah dari 'name'
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20|unique:tb_pelanggans,no_hp',
            'username' => 'required|string|max:255|unique:users,username', // Ditambahkan
            'email' => 'required|string|email|max:255|unique:users,email',
            'nik' => 'required|string|digits:16|unique:users,nik', // <-- NIK DITAMBAHKAN
            'password' => 'required|string|min:8|confirmed',
        ]);
        // =============================================================

        // Dapatkan user yang sedang login (dari tabel 'users')
        $adminUser = Auth::user();
        if (!$adminUser) {
             return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        // Cari profil admin di 'tb_admins' berdasarkan 'id_user'
        $adminProfile = Admin::where('id_user', $adminUser->id)->first();

        // Periksa apakah profil admin ditemukan dan punya id_company
        if (!$adminProfile || !$adminProfile->id_company) {
             return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Gagal menambahkan pelanggan. Akun Admin tidak terhubung ke company manapun.');
        }
        
        // Ambil ID company dari profil admin ('tb_admins')
        $companyId = $adminProfile->id_company;


        // 2. Gunakan Database Transaction
        DB::beginTransaction();

        try {
            // 3. Buat Akun User baru
            // ===================== PERBARUI USER::CREATE =====================
            // Disesuaikan dengan $fillable model User: username, email, password, role, nik
            $user = User::create([
                'username' => $request->username, // Diambil dari form
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pelanggan', // ASUMSI: role untuk pewlanggan adalah 'pelanggan'
                'nik' => $request->nik, // <-- NIK DITAMBAHKAN
            ]);
            // =================================================================

            // 4. Buat Data Pelanggan baru
            // ===================== PERBARUI PELANGGAN::CREATE =====================
            Pelanggan::create([
                'nama' => $request->nama, // Diubah dari 'name'
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'id_user' => $user->id, // Ambil ID dari user yang baru dibuat
                'id_company' => $companyId, // Ambil ID dari admin yang login
            ]);
            // ===================================================================

            // 5. Jika sukses, commit transaction
            DB::commit();

            // ===================== PERBARUI PESAN SUKSES =====================
            return redirect()->route('admin.pelanggan.index')
                         ->with('success', 'Pelanggan baru (' . $request->nama . ') berhasil ditambahkan.');

        } catch (Exception $e) {
            // 6. Jika gagal, rollback semua perubahan
            DB::rollBack();
            // Tampilkan pesan error
            return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // 1. Temukan pelanggan dan user terkait
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) {
            return redirect()->route('admin.pelanggan.index')->with('error', 'Data pelanggan tidak ditemukan.');
        }
        // Asumsi relasi user() ada di model Pelanggan
        $user = $pelanggan->user;
        if (!$user) {
            return redirect()->route('admin.pelanggan.index')->with('error', 'Data user terkait tidak ditemukan.');
        }

        // 2. Validasi Input (dengan 'ignore' rule untuk data unik)
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tb_pelanggans')->ignore($pelanggan->id),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'nik' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('users')->ignore($user->id),
            ],
            // Password Boleh Kosong (nullable), tapi jika diisi, harus min 8 & confirmed
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 3. Gunakan Database Transaction
        DB::beginTransaction();
        try {
            // 4. Update data User
            $userData = [
                'username' => $request->username,
                'email' => $request->email,
                'nik' => $request->nik,
            ];
            // Hanya update password jika diisi
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // 5. Update data Pelanggan
            $pelanggan->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
            ]);

            // 6. Jika sukses, commit
            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                         ->with('success', 'Data Pelanggan (' . $request->nama . ') berhasil diperbarui.');

        } catch (\Throwable $th) {
            // 7. Jika gagal, rollback
            DB::rollBack();
            // Tampilkan error di halaman
            // CATATAN: Ini tidak akan membuka kembali modal edit,
            // tapi akan menampilkan banner error di atas tabel.
            return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Gagal memperbarui data: ' . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        // 1. Temukan pelanggan
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) {
            return redirect()->route('admin.pelanggan.index')->with('error', 'Data pelanggan tidak ditemukan.');
        }

        // Simpan nama untuk pesan sukses
        $namaPelanggan = $pelanggan->nama;
        // Simpan user-nya SEBELUM menghapus pelaanggan
        $userToDelete = $pelanggan->user;

        DB::beginTransaction();
        try {
            
            // ====================== PERUBAHAN DI SINI ======================
            
            // 2. Hapus data PELANGGAN (child) DULU
            // Ini akan menghapus baris di 'tb_pelanggans'
            $pelanggan->delete();

            // 3. Hapus data USER (parent) KEMUDIAN (jika ada)
            // Ini akan menghapus baris di 'users'
            if ($userToDelete) {
                $userToDelete->delete();
            }
            
            // ==================== AKHIR PERUBAHAN ====================

            // 4. Commit
            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                         ->with('success', 'Data pelanggan (' . $namaPelanggan . ') dan akun terkait berhasil dihapus.');

        } catch (\Throwable $th) {
            // 5. Rollback jika gagal
            DB::rollBack();
            return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Gagal menghapus data: ' . $th->getMessage());
        }
    }
}