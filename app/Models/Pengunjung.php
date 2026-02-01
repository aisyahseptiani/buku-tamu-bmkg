<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    use HasFactory;

    protected $table = 'pengunjungs'; // ⬅️ PENTING

    protected $fillable = [
        'nama',
        'instansi',
        'tujuan',
        'no_hp',
        'foto',
        'tanda_tangan',
        'ip_address',
        'lokasi',
    ];
}