<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;
    protected $table = 'tb_pembayarans';
    protected $fillable = ['tanggal', 'abonemen', 'denda', 'total_tagihan', 'id_pelanggan', 'id_pemakaian'];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }

    public function pemakaian()
    {
        return $this->belongsTo(Pemakaian::class, 'id_pemakaian');
    }
}
