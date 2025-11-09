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
        Schema::create('tb_pemakaians', function (Blueprint $table) {
            $table->id();
            $table->integer('meter_awal');
            $table->integer('meter_akhir');
            $table->string('foto');
            $table->integer('total_pakai');
            $table->string('kd_product');
            $table->integer('tarif');
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger(column: 'id_petugas');
            $table->unsignedBigInteger('id_pelanggan');
            $table->timestamps();

            $table->foreign('id_petugas')->references('id')->on('tb_petugas');
            $table->foreign('id_pelanggan')->references('id')->on('tb_pelanggans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_pemakaians');
    }
};
