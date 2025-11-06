<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;
    protected $table = 'tb_harga';
    protected $fillable = [
        'id_company',
        'nama_product',
        'kode_product',
        'tipe',
        'harga_product',
        'biaya_admin',
        'denda',
    ];
    protected $casts = [
        'harga_product' => 'decimal:2',
        'biaya_admin'   => 'decimal:2',
        'denda'         => 'decimal:2',
    ];

    /**
     * Relasi ke model Company (jika Anda punya).
     * Ini mendefinisikan bahwa satu harga 'milik' satu perusahaan.
     */
    public function company()
    {
        // Ganti 'App\Models\Company' jika path model Anda berbeda
        return $this->belongsTo(Company::class, 'id_company');
    }
}
