<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'PT. Waterpay',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 10',
            'no_rekening' => '1234567890',
            'pj' => 'Super Admin', 
        ]);
    }
}