<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'tb_maintenances';

    protected $fillable = [
        'keluhan_id',
        'foto',
        'deskripsi',
        'tanggal',
    ];

    // Relasi balik ke Keluhan
    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class, 'keluhan_id');
    }
}