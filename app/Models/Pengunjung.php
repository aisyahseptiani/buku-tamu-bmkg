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
        'tanggal_kunjungan',
    ];

    public function index()
    {
        Pengunjung::create([
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);

        return view('user.home');
    }
}