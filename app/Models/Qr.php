<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    protected $table = 'qrs'; // ⬅️ SESUAIKAN DENGAN DATABASE

    protected $fillable = [
        'token',
        'tanggal',
        'is_active',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_active' => 'boolean',
    ];
}
