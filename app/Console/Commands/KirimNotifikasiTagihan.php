<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemakaian;
use App\Models\Pelanggan; // Pastikan Model Pelanggan di-import
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KirimNotifikasiTagihan extends Command
{
    /**
     * Nama perintah untuk dipanggil di terminal.
     * Contoh penggunaan: php artisan app:kirim-notifikasi-tagihan
     */
    protected $signature = 'app:kirim-notifikasi-tagihan';

    /**
     * Deskripsi perintah.
     */
    protected $description = 'Mengirim pesan WhatsApp (Wablas) ke pelanggan yang memiliki tagihan belum lunas';

    /**
     * Logika Utama Robot
     */
    public function handle()
    {
        $this->info('Memulai proses pengiriman notifikasi WA (Wablas)...');

        // 1. Ambil Tagihan Status 'Belum Bayar' atau 'Pending'
        // Kita gunakan 'with' agar lebih efisien, tapi nanti kita cek manual juga
        $tagihanList = Pemakaian::with(['pelanggan', 'kode_product'])
            ->whereIn('status_pembayaran', ['belum_bayar', 'pending'])
            ->get();

        if ($tagihanList->isEmpty()) {
            $this->info('Tidak ada tagihan yang perlu dikirim.');
            return;
        }

        $bar = $this->output->createProgressBar(count($tagihanList));
        $bar->start();

        // Ambil Config dari .env
        $wablasDomain = env('WABLAS_DOMAIN'); 
        $wablasToken  = env('WABLAS_TOKEN');

        if (!$wablasDomain || !$wablasToken) {
            $this->error('Konfigurasi Wablas (Domain/Token) belum diset di .env!');
            return;
        }

        // Pastikan tidak ada slash berlebih di akhir domain
        $wablasDomain = rtrim($wablasDomain, '/');

        foreach ($tagihanList as $tagihan) {
            
            // --- LOGIKA PENGAMBILAN DATA PELANGGAN (DIPERBAIKI) ---
            
            // Opsi 1: Menggunakan Relasi (Lebih Cepat)
            $pelanggan = $tagihan->pelanggan;

            // Opsi 2: Cari Manual (Backup jika relasi gagal/null)
            if (!$pelanggan && $tagihan->id_pelanggan) {
                $pelanggan = Pelanggan::find($tagihan->id_pelanggan);
            }

            // Validasi: Jika data pelanggan tetap tidak ditemukan, skip tagihan ini
            if (!$pelanggan) {
                $this->error("Skip: Data pelanggan tidak ditemukan untuk Tagihan ID {$tagihan->id}");
                Log::warning("Data pelanggan hilang untuk Tagihan ID {$tagihan->id}");
                continue;
            }

            // Validasi: Cek Nomor HP
            $noHp = $pelanggan->no_hp;
            if (empty($noHp)) {
                $this->error("Skip: Pelanggan {$pelanggan->nama} tidak memiliki No HP.");
                continue;
            }

            // --- PERSIAPAN PESAN ---
            $bulan = Carbon::parse($tagihan->created_at)->translatedFormat('F Y');
            $total = number_format($tagihan->total_tagihan, 0, ',', '.');
            $nama  = $pelanggan->nama;

            // Format Pesan
            $pesan  = "Halo kak *$nama*,\n\n";
            $pesan .= "Tagihan air periode *$bulan* sudah terbit.\n";
            $pesan .= "Total Tagihan: *Rp $total*\n\n";
            $pesan .= "Mohon segera lakukan pembayaran melalui aplikasi Waterpay untuk menghindari denda.\n";
            $pesan .= "Terima kasih.";

            // --- KIRIM KE API WABLAS V2 ---
            try {
                $response = Http::withHeaders([
                    'Authorization' => $wablasToken, 
                    'Content-Type'  => 'application/json',
                ])->post($wablasDomain . '/api/v2/send-message', [
                    'data' => [ 
                        [
                            'phone' => $noHp,
                            'message' => $pesan,
                        ]
                    ]
                ]);

                $resJson = $response->json();
                
                // Cek hasil kirim
                if ($response->failed() || (isset($resJson['status']) && $resJson['status'] === false)) {
                    $errMsg = $resJson['message'] ?? $response->body();
                    $this->error("Gagal kirim ke $nama ($noHp): " . $errMsg);
                    
                    if (str_contains(strtolower($errMsg), 'disconnected') || str_contains(strtolower($errMsg), 'expired')) {
                        $this->warn("⚠️  PERHATIAN: WhatsApp terputus. Silakan Scan QR ulang di Dashboard Wablas.");
                    }
                    
                    Log::error("Wablas Error: " . $response->body());
                } else {
                    // Jika sukses (opsional: tampilkan info di terminal)
                    // $this->info("Terkirim ke $nama");
                }

            } catch (\Exception $e) {
                $this->error("Error koneksi WA: " . $e->getMessage());
            }

            $bar->advance();
            
            // Jeda 2 detik agar aman
            sleep(2);
        }

        $bar->finish();
        $this->newLine();
        $this->info('Selesai! Notifikasi telah dikirim.');
    }
}