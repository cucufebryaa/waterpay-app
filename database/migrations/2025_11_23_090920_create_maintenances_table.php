<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_maintenances', function (Blueprint $table) {
            $table->id();
            // Relasi ke tb_keluhans
            $table->unsignedBigInteger('keluhan_id'); 
            $table->string('foto')->nullable(); // Path foto bukti pengerjaan
            $table->text('deskripsi');
            $table->dateTime('tanggal'); // Tanggal pengerjaan
            $table->timestamps();

            // Foreign Key
            $table->foreign('keluhan_id')->references('id')->on('tb_keluhans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_maintenances');
    }
};