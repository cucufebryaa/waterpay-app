<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tb_pemakaians', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada untuk mencegah error duplicate column
            if (!Schema::hasColumn('tb_pemakaians', 'status_pembayaran')) {
                $table->enum('status_pembayaran', ['belum_bayar', 'pending', 'lunas', 'kadaluarsa'])
                      ->default('belum_bayar')
                      ->after('tarif');
            }

            if (!Schema::hasColumn('tb_pemakaians', 'total_tagihan')) {
                $table->decimal('total_tagihan', 15, 2)->default(0)->after('status_pembayaran');
            }

            // INI YANG MENYEBABKAN ERROR (KOLOM KURANG)
            if (!Schema::hasColumn('tb_pemakaians', 'total_akhir')) {
                $table->decimal('total_akhir', 15, 2)->nullable()->after('total_tagihan');
            }

            if (!Schema::hasColumn('tb_pemakaians', 'tgl_bayar')) {
                $table->dateTime('tgl_bayar')->nullable()->after('total_akhir');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_pemakaians', function (Blueprint $table) {
            $table->dropColumn(['status_pembayaran', 'total_tagihan', 'total_akhir', 'tgl_bayar']);
        });
    }
};