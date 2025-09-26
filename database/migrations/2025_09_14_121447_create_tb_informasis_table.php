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
        Schema::create('tb_informasis', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->text('pesan');
            $table->unsignedBigInteger('id_company');
            $table->timestamps();

            $table->foreign('id_company')->references('id')->on('tb_companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_informasis');
    }
};
