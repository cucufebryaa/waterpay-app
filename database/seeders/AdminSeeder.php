<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Company;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil data company yang sudah dibuat
        $company = Company::first();

        // Ambil data user yang sudah dibuat (role admin)
        $user = User::where('role', 'admin')->first();

        Admin::create([
            'name' => 'Admin Utama',
            'alamat' => 'Jl. Admin No. 1',
            'no_hp' => '08111222333',
            'id_user' => $user->id,
            'id_company' => $company->id,
        ]);
    }
}