<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survei extends Model
{
    protected $table = 'survei';

    protected $fillable = [
        'pengunjung_id',
        'jawaban',
        'saran',
        'masukan',
    ];

    protected $casts = [
        'jawaban' => 'array',
    ];
}
