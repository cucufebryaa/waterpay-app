<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    protected $table = 'tb_admins';
    protected $fillable = [
        'name', // <-- Pastikan kolom ini ada
        'alamat',
        'email',
        'no_hp',
        'id_user',
        'id_company',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
