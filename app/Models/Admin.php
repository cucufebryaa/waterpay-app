<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'tb_admins';

    // Nama field yang bisa diisi (sesuaikan dengan migrasi yang telah kita buat)
    protected $fillable = [
        'user_id',
        'id_company',
        'nama_lengkap',
        'alamat',
        'no_hp',
        'nik', // Asumsi field 'nik' ada jika diminta di tabel
    ];

    /**
     * Relasi ke Model User (untuk login/username/email)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Model Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
