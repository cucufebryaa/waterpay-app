<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahkan baris ini
use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    use HasFactory;
    protected $table = 'tb_informasis';
    protected $fillable = ['tanggal', 'pesan', 'id_company'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}