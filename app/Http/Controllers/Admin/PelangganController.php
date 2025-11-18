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
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20|unique:tb_pelanggans,no_hp',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'nik' => 'required|string|digits:16|unique:users,nik',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $adminUser = Auth::user();
        if (!$adminUser) {
             return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Sesi Anda telah berakhir. Silakan login kembali.');
        }

        $adminProfile = Admin::where('id_user', $adminUser->id)->first();

        if (!$adminProfile || !$adminProfile->id_company) {
             return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Gagal menambahkan pelanggan. Akun Admin tidak terhubung ke company manapun.');
        }
        
        $companyId = $adminProfile->id_company;

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'pelanggan',
                'nik' => $request->nik,
            ]);
            
            Pelanggan::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'id_user' => $user->id,
                'id_company' => $companyId,
            ]);
            DB::commit();

            return redirect()->route('admin.pelanggan.index')
                         ->with('success', 'Pelanggan baru (' . $request->nama . ') berhasil ditambahkan.');

        } catch (Exception $e) {
            DB::rollBack();
            // Tampilkan pesan error
            return redirect()->route('admin.pelanggan.index')
                         ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::find($id);
        if (!$pelanggan) {
            return redirect()->route('admin.pelanggan.index')->with('error', 'Data pelanggan tidak ditemukan.');
        }
        $user = $pelanggan->user;
        if (!$user) {
            return redirect()->route('admin.pelanggan.index')->with('error', 'Data user terkait tidak ditemukan.');
        }
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => ['required','string','max:20', Rule::unique('tb_pelanggans')->ignore($pelanggan->id),],
            'username' => ['required','string','max:255',Rule::unique('users')->ignore($user->id),],
            'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($user->id),],
            'nik' => ['required','string','digits:16',Rule::unique('users')->ignore($user->id),],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $userData = [
                'username' => $request->username,
                'email' => $request->email,
                'nik' => $request->nik,
            ];
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
        $namaPelanggan = $pelanggan->nama;
        $userToDelete = $pelanggan->user;

        DB::beginTransaction();
        try {
            $pelanggan->delete();
            if ($userToDelete) {
                $userToDelete->delete();
            }
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