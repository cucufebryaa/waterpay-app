<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    use HasFactory;
    protected $table = 'tb_keluhans';
    protected $fillable = [
        'tanggal',
        'keluhan',
        'status',
        'id_pelanggan',
        'id_petugas',
        'id_company'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class,'id_petugas');
    }

    public function id_company()
    {
        return $this->belongsTo(Company::class,'id_company');
    }
}
