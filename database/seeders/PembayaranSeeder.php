<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PembayaranSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Get Company for 'tata123' OR 'PDAM Dummy'
        // Prioritize 'tata123' as requested by user
        $targetUser = DB::table('users')->where('username', 'tata123')->first();
        $companyId = null;

        if ($targetUser) {
            $admin = DB::table('tb_admins')->where('id_user', $targetUser->id)->first();
            if ($admin && $admin->id_company) {
                $companyId = $admin->id_company;
                $this->command->info("Seeding for Company ID: $companyId (User: tata123)");
            }
        }

        if (!$companyId) {
            $companyId = DB::table('tb_companies')->value('id');
        }

        if (!$companyId) {
            $companyId = DB::table('tb_companies')->insertGetId([
                'nama_perusahaan' => 'PDAM Dummy',
                'no_hp' => '08123456789',
                'alamat' => 'Jl. Dummy No. 1',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2a. Create Dummy Product (Harga) if not exists
        $productId = DB::table('tb_harga')->value('id');
        if (!$productId) {
            $productId = DB::table('tb_harga')->insertGetId([
                'id_company' => $companyId,
                'nama_product' => 'Air Rumah Tangga',
                'kode_product' => 'AIR-01',
                'tipe' => 'progresif',
                'harga_product' => 5000,
                'biaya_admin' => 2500,
                'denda' => 50000,
                'batas_waktu_denda' => now()->addDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2b. Create Dummy Petugas (Officer) - 5 people
        $petugasIds = [];
        for ($i = 0; $i < 5; $i++) {
            // Create User for Petugas
            $userId = DB::table('users')->insertGetId([
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'petugas',
                'nik' => $faker->numerify('################'), // Use numerify for NIK
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $petugasIds[] = DB::table('tb_petugas')->insertGetId([
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'id_company' => $companyId,
                'id_user' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Create Dummy Pelanggan (Customer) - 15 people
        $pelangganIds = [];
        for ($i = 0; $i < 15; $i++) {
            // Create User for Pelanggan
            $userId = DB::table('users')->insertGetId([
                'username' => $faker->userName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'pelanggan',
                'nik' => $faker->numerify('################'), // Use numerify for NIK
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $pelangganIds[] = DB::table('tb_pelanggans')->insertGetId([
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'no_hp' => $faker->phoneNumber,
                'id_company' => $companyId,
                'id_user' => $userId,
                'id_product' => $productId, // Linked to existing/created product
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Create Transactions (Pemakaian & Pembayaran) - 50 records
        for ($i = 0; $i < 50; $i++) {
            $month = $faker->numberBetween(1, 12);
            $year = $faker->numberBetween(2024, 2025);
            $date = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            
            // Randomly select linked entities
            $idPelanggan = $faker->randomElement($pelangganIds);
            $idPetugas = $faker->randomElement($petugasIds);

            $meterAwal = $faker->numberBetween(100, 1000);
            $meterAkhir = $meterAwal + $faker->numberBetween(10, 50);
            $totalPakai = $meterAkhir - $meterAwal;
            $tarif = 2000;
            $totalTagihan = $totalPakai * $tarif;

            // Insert Pemakaian (Usage)
            $idPemakaian = DB::table('tb_pemakaians')->insertGetId([
                'meter_awal' => $meterAwal,
                'meter_akhir' => $meterAkhir,
                'total_pakai' => $totalPakai,
                'tarif' => $tarif,
                'total_tagihan' => $totalTagihan,
                'total_akhir' => $totalTagihan, // Simplified
                'id_company' => $companyId,
                'id_petugas' => $idPetugas,
                'id_pelanggan' => $idPelanggan,
                'kd_product' => 'AIR-01',
                'status_pembayaran' => 'lunas', // Assuming seeded payments are mostly paid
                'tgl_bayar' => $date->copy()->addDays(rand(1, 28)),
                'foto' => 'dummy.jpg', // Add dummy photo
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Insert Pembayaran (Payment)
            $status = $faker->randomElement(['success', 'success', 'success', 'pending', 'failed']); // Weighted to success
            $tglBayar = ($status == 'success') ? $date->copy()->addDays(rand(1, 25)) : null;

            DB::table('tb_pembayarans')->insert([
                'xendit_id' => $faker->uuid,
                'xendit_external_id' => 'INV-' . $faker->randomNumber(8),
                'payment_channel' => $faker->randomElement(['BRI', 'BCA', 'BNI', 'MANDIRI', 'ALFAMART']),
                'jumlah_tagihan' => $totalTagihan,
                'biaya_admin' => 2500,
                'total_bayar' => $totalTagihan + 2500,
                'status' => $status,
                'tanggal_bayar' => $tglBayar,
                'id_pelanggan' => $idPelanggan,
                'id_pemakaian' => $idPemakaian,
                'id_company' => $companyId,
                'created_at' => $tglBayar ?? $date,
                'updated_at' => $tglBayar ?? $date,
            ]);
        }
    }
}
