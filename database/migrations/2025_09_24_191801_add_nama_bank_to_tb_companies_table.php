<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_companies', function (Blueprint $table) {
            $table->string('nama_bank')->after('alamat');
            $table->string('username')->after('no_rekening');
            $table->string('status')->default('pending');
        });
    }

    public function down(): void
    {
        Schema::table('tb_companies', function (Blueprint $table) {
            $table->dropColumn('nama_bank');
            $table->dropColumn('username');
            $table->dropColumn('status');
        });
    }
};