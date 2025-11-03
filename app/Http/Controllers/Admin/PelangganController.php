<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    private function getCompanyId()
    {
        return auth()->user()->admin->id_company;
    }
    public function index()
    {
        // Hanya ambil pelanggan yang perusahaannya = perusahaan admin
        $pelanggan = Pelanggan::where('id_company', $this->getCompanyId())
                            ->latest()
                            ->get();
        
        return view('admin.kelola-pengguna', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pelanggans',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            Pelanggan::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'password' => Hash::make($validated['password']),
                // Tambahkan 'id_user' atau 'id_company' jika perlu
                // 'id_user' => auth()->id(), 
            ]);

            return redirect()->route('admin.pelanggan.index')
                             ->with('success', 'Pelanggan baru berhasil ditambahkan.');

        } catch (\Exception $e) {
            Log::error('Error saat simpan pelanggan: ' . $e->getMessage());
            return redirect()->route('admin.pelanggan.index')
                             ->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('pelanggans')->ignore($pelanggan->id), // Abaikan email milik sendiri
            ],
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:500',
            // Password bersifat opsional saat update
            'password' => 'nullable|string|min:8|confirmed', 
        ]);

        try {
            // Siapkan data untuk diupdate
            $dataToUpdate = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
            ];

            // Hanya update password jika diisi
            if (!empty($validated['password'])) {
                $dataToUpdate['password'] = Hash::make($validated['password']);
            }

            $pelanggan->update($dataToUpdate);

            return redirect()->route('admin.pelanggan.index')
                             ->with('success', 'Data pelanggan berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Error saat update pelanggan: ' . $e->getMessage());
            return redirect()->route('admin.pelanggan.index')
                             ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    public function destroy($id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->delete();

            return redirect()->route('admin.pelanggan.index')
                             ->with('success', 'Data pelanggan berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Error saat hapus pelanggan: ' . $e->getMessage());
            // Cek jika error karena foreign key constraint
            if ($e->getCode() == '23000') {
                 return redirect()->route('admin.pelanggan.index')
                             ->with('error', 'Data pelanggan tidak dapat dihapus karena terkait dengan data lain.');
            }
            
            return redirect()->route('admin.pelanggan.index')
                             ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}