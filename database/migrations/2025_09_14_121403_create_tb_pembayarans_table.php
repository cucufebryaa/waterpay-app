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
        Schema::create('tb_pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('xendit_id')->nullable()->index(); 
            $table->string('xendit_external_id')->nullable();
            $table->string('payment_channel')->nullable(); // Misal: BRI, OVO
            $table->string('payment_url')->nullable(); // Link bayar
            $table->decimal('jumlah_tagihan', 15, 2); // Tagihan asli
            $table->decimal('denda', 15, 2)->default(0); // Nominal denda saat dibayar
            $table->decimal('biaya_admin', 15, 2)->default(0); // Biaya admin gateway
            $table->decimal('total_bayar', 15, 2); // Total yang harus dibayar user
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->dateTime('tanggal_bayar')->nullable();
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_pemakaian');
            $table->unsignedBigInteger('id_company');
            $table->timestamps();
            $table->foreign('id_pelanggan')->references('id')->on('tb_pelanggans');
            $table->foreign('id_pemakaian')->references('id')->on('tb_pemakaians');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pembayarans');
    }
};
