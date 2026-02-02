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
        'ip_address',
    ];

    public function survei()
    {
        return $this->hasOne(Survei::class);
    }
}