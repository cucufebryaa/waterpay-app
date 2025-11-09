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
            $table->date('tanggal');
            $table->integer('abonemen');
            $table->integer('denda')->nullable();
            $table->integer('total_tagihan');
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
