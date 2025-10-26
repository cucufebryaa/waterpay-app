<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Petugas;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $usersQuery = User::whereIn('role', ['admin', 'petugas', 'pelanggan'])
                        ->with([
                            'admin.company',
                            'petugas.company',
                            'pelanggan.company'
                        ]);
        if ($search) {
            $usersQuery->where('username', 'like', '%' . $search . '%');
        }
        $users = $usersQuery->orderBy('id', 'asc')->get();
        $userData = $users->map(function ($user) {
            
            $relatedModel = $user->admin ?? $user->petugas ?? $user->pelanggan;
            $company = $relatedModel?->company;
            $baseUserInfo = $user->toArray();
            unset($baseUserInfo['password']);
            
            $roleSpecificInfo = $relatedModel ? $relatedModel->toArray() : null;
            $companyInfo = $company ? $company->toArray() : null;
            $namaLengkap = $company?->penanggung_jawab ?? $relatedModel?->name ?? 'N/A';
            $alamat = $company?->alamat ?? $relatedModel?->alamat ?? 'N/A';
            $namaPerusahaan = $company?->nama_perusahaan ?? 'N/A';

            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role' => ucfirst($user->role), 
                'nama_lengkap' => $namaLengkap,
                'alamat' => $alamat,
                'nama_perusahaan' => $namaPerusahaan,
                'base_user_info' => $baseUserInfo,
                'role_specific_info' => $roleSpecificInfo,
                'company_info' => $companyInfo
            ];
        });
        return view('superadmin.users.user_management_index', compact('userData', 'search'));
    }

    public function destroy(User $user)
    {
        if ($user->role === 'superadmin') {
            return back()->with('error', 'Super Admin tidak dapat dihapus.');
        }
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }
        DB::beginTransaction();

        try {
            $user->load('admin', 'petugas', 'pelanggan');
            if ($user->admin) {
                $user->admin->delete();
            } elseif ($user->petugas) {
                $user->petugas->delete();
            } elseif ($user->pelanggan) {
                $user->pelanggan->delete();
            }
            $user->delete();
            DB::commit();
            return redirect()->route('superadmin.management-users.index')
                             ->with('success', 'User ' . $user->username . ' berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DELETE USER FAILED: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus user.');
        }
    }
}