<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyApprovalController extends Controller
{
    public function index()
    {
        // Ambil SEMUA data perusahaan, eager load relasi 'owner' (User).
        // Kita gunakan 'owner' karena field 'penanggung_jawab' adalah ID User yang mendaftar.
        $companies = Company::with('admins') 
                               ->orderBy('id', 'asc') // Tampilkan yang terbaru di atas
                               ->get();

        // Menggunakan variabel 'companies' untuk menampung semua data riwayat dan pending
        return view('superadmin.approval-company', compact('companies')); // Menggunakan nama view yang sebelumnya kita sepakati
    }

    /**
     * Proses persetujuan (Approve) perusahaan.
     */
    public function approve(Company $company)
    {
        // 1. Validasi Status
        if ($company->status !== 'pending') {
            return redirect()->route('superadmin.companies.pending')->with('error', 'Perusahaan ini sudah diproses. Status: ' . $company->status);
        }

        // 2. Update Status Company
        try {
            // Menggunakan transaction jika diperlukan, tapi untuk 2 update sederhana ini tidak wajib
            $company->status = 'approved';
            $company->save();

            // 3. Update Role User (Penanggung Jawab/Admin Perusahaan)
            $owner = $company->owner;
            if ($owner) {
                 // Asumsi: Saat register, user memiliki role 'unverified' atau 'user'.
                 // Di sini kita ubah role-nya menjadi 'company_admin' (sesuaikan dengan role di sistem Anda)
                 $owner->role = 'admin'; 
                 $owner->save();
            } else {
                // Log atau handling jika user penanggung jawab tidak ditemukan
                return redirect()->route('superadmin.companies.pending')->with('warning', 'Perusahaan disetujui, namun User Penanggung Jawab tidak ditemukan.');
            }

            return redirect()->route('superadmin.companies.pending')->with('success', 'Perusahaan ' . $company->name . ' berhasil disetujui. Admin perusahaan telah diaktifkan.');

        } catch (\Exception $e) {
            // Handling error database
            return redirect()->route('superadmin.companies.pending')->with('error', 'Gagal memproses persetujuan. Error: ' . $e->getMessage());
        }
    }

    /**
     * Proses penolakan (Reject) perusahaan.
     */
    public function reject(Company $company)
    {
        // 1. Validasi Status
        if ($company->status !== 'pending') {
            return redirect()->route('superadmin.companies.pending')->with('error', 'Perusahaan ini sudah diproses. Status: ' . $company->status);
        }

        // 2. Update Status Company menjadi 'rejected'
        try {
            $company->status = 'rejected';
            $company->save();

            // Opsional: Blokir atau nonaktifkan user terkait jika ditolak
            // $owner = $company->owner;
            // if ($owner) {
            //      $owner->is_active = false;
            //      $owner->save();
            // }

            return redirect()->route('superadmin.companies.pending')->with('success', 'Perusahaan ' . $company->name . ' berhasil ditolak.');

        } catch (\Exception $e) {
            return redirect()->route('superadmin.companies.pending')->with('error', 'Gagal memproses penolakan.');
        }
    }
}
