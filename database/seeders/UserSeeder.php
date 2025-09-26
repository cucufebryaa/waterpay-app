<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [[
            'username' => 'superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'nik' => '1234567890123456',
            ],
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'nik' => '1234567890123457',
            ],
            [
                'username' => 'petugas',
                'email' => 'petugas@example.com',
                'password' => Hash::make('password123'),
                'role' => 'petugas',
                'nik' => '1234567890123458',
            ],
            [
                'username' => 'pelanggan',
                'email' => 'pelanggan@example.com',
                'password' => Hash::make('password123'),
                'role' => 'pelanggan',
                'nik' => '1234567890123459',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}