<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survei extends Model
{
    use HasFactory;

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

    public function pengunjung()
    {
        return $this->belongsTo(Pengunjung::class);
    }
}
