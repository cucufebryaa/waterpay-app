<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;
    protected $table = 'tb_pemakaians';
    protected $fillable = ['meter_awal', 'meter_akhir', 'foto', 'total_pakai', 'kd_product', 'tarif', 'id_company', 'id_petugas', 'id_pelanggan'];

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pemakaian');
    }

    public function kode_product()
    {
        return $this->belongsTo(Harga::class, 'kd_product', 'kd_product');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'id_company');
    }
}
