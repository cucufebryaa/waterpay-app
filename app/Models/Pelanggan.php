<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'tb_pelanggans';
    
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'id_user',
        'id_company',
        'id_product' // <--- PENTING: Tambahkan ini agar bisa disimpan/diedit
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

     public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function keluhan()
    {
        return $this->hasMany(Keluhan::class, 'id_pelanggan');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_pelanggan');
    }

    public function pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'id_pelanggan');
    }
    public function kode_product()
    {
        return $this->belongsTo(Harga::class, 'id_product', 'id');
    }
}