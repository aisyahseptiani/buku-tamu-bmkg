<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Qr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class QrController extends Controller
{
    public function index() {
        $tanggal = Carbon::today();

        $qr = Qr::whereDate('tanggal', $tanggal)
            ->where('is_active', 1)
            ->first();

        return view('admin.qr.index', compact('qr', 'tanggal'));
    }

    public function generate() {
        $tanggal = Carbon::today();

        // CEK: apakah QR hari ini sudah ada
        $qrHariIni = Qr::whereDate('tanggal', $tanggal)->first();

        if ($qrHariIni) {
            return redirect()
                ->route('admin.qr.index')
                ->with('error', 'QR untuk hari ini sudah dibuat');
        }

        // nonaktifkan QR aktif sebelumnya
        Qr::where('is_active', 1)->update(['is_active' => 0]);

        // buat QR baru
        Qr::create([
            'token' => Str::random(40),
            'tanggal' => $tanggal,
            'is_active' => 1
        ]);

        return redirect()
            ->route('admin.qr.index')
            ->with('success', 'QR hari ini berhasil dibuat');
    }
}