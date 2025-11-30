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
        Schema::create('tb_keluhans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('keluhan');
            $table->enum('status', ['open','delegated','onprogress','completed','rejected'])->default('open');
            $table->unsignedBigInteger('id_petugas');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_company');
            $table->timestamps();
            $table->foreign('id_pelanggan')->references('id')->on('tb_pelanggans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_keluhans');
    }
};
