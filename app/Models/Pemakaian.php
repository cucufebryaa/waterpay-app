<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    use HasFactory;
    protected $table = 'tb_pemakaians';
    
    protected $fillable = [
        // Kolom Data Petugas
        'meter_awal', 
        'meter_akhir', 
        'foto', 
        'total_pakai', 
        'kd_product', 
        'tarif', 
        'id_company', 
        'id_petugas', 
        'id_pelanggan',
        
        // --- KOLOM BARU (WAJIB DITAMBAHKAN) ---
        'total_tagihan',     // Menyimpan nominal tagihan awal
        'status_pembayaran', // Menyimpan status: belum_bayar, pending, lunas
        'total_akhir',       // Menyimpan total yang dibayarkan (setelah denda)
        'tgl_bayar'          // Menyimpan waktu pelunasan
    ];

    // TAMBAHAN PENTING: Casting tipe data agar tidak error saat format tanggal/angka
    protected $casts = [
        'tgl_bayar' => 'datetime',
        'total_tagihan' => 'decimal:2',
        'total_akhir' => 'decimal:2',
        'tarif' => 'decimal:2',
    ];

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
        // PERBAIKAN RELASI:
        // Karena kolom 'kd_product' di tabel ini menyimpan KODE STRING (misal "RT-01"),
        // Maka parameter ke-3 harus merujuk ke kolom 'kode_product' di tabel Harga, BUKAN 'id'.
        return $this->belongsTo(Harga::class, 'kd_product', 'kode_product');
    }

    public function company()
    {
        return $this->belongsTo(Company::class,'id_company');
    }
}