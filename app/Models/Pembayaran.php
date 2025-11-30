<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'tb_pembayarans';

    // PASTIKAN SEMUA KOLOM INI ADA
    protected $fillable = [
        'xendit_id',
        'xendit_external_id',
        'payment_channel',
        'payment_url',
        'jumlah_tagihan',
        'denda',
        'biaya_admin',
        'total_bayar',
        'status', 
        'tanggal_bayar',
        'id_pelanggan',
        'id_pemakaian', // <--- INI WAJIB ADA
        'id_company'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'total_bayar' => 'decimal:2',
        'denda' => 'decimal:2',
    ];

    public function pemakaian()
    {
        return $this->belongsTo(Pemakaian::class, 'id_pemakaian');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}