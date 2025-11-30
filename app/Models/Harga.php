<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Harga extends Model
{
    use HasFactory;
    protected $table = 'tb_harga';
    protected static function booted()
    {
        static::creating(function ($model) {
            // Cek jika 'batas_waktu_denda' belum diisi saat proses create
            if (empty($model->batas_waktu_denda)) {
                // Set nilainya ke tanggal 10 bulan ini
                $model->batas_waktu_denda = Carbon::now()->setDay(10)->toDateString();
            }
        });
    }
    protected $fillable = [
        'id_company',
        'nama_product',
        'kode_product',
        'tipe',
        'harga_product',
        'biaya_admin',
        'denda',
        'batas_waktu_denda',
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

    public function pemakaian()
    {
        return $this->hasMany(pemakaian::class,'kd_product');
    }
}
