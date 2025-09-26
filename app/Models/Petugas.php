<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;
    protected $table = 'tb_petugas';
    protected $fillable = ['name', 'alamat', 'no_hp', 'id_user', 'id_company'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function pemakaian()
    {
        return $this->hasMany(Pemakaian::class, 'id_petugas');
    }
}
