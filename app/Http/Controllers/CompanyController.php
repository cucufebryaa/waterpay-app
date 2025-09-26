<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::query();

        if ($request->filled('search')) {
            $companies->where('name', 'like', '%' . $request->search . '%');
        }

        $companies = $companies->get();

        return view('superadmin.approval-company', compact('companies'));
    }

    public function approve(Company $company)
    {
        $company->status = 'approved';
        $company->save();
        return back()->with('success', 'Perusahaan berhasil disetujui.');
    }

    public function reject(Company $company)
    {
        $company->status = 'rejected';
        $company->save();
        return back()->with('success', 'Perusahaan berhasil ditolak.');
    }
}