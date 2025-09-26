<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $table = 'tb_companies';
    protected $fillable = [
        'name',
        'no_hp',
        'alamat',
        'no_rekening',
        'pj', // Pastikan kolom ini ada dan namanya benar
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class, 'id_company');
    }

    public function petugas()
    {
        return $this->hasMany(Petugas::class, 'id_company');
    }

    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'id_company');
    }

    public function informasi()
    {
        return $this->hasMany(Informasi::class, 'id_company');
    }
}
