<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengunjung;
use Carbon\Carbon;

class PengunjungController extends Controller
{
    /**
     * ============================
     * STEP 1 - FORM DATA DIRI
     * ============================
     */
    public function step1(Request $request)
    {
        // MODE PREVIEW (LOCAL ONLY)
        if (app()->environment('local') && $request->query('preview')) {
            session(['qr_token' => 'PREVIEW']);
            return view('pengunjung.step1');
        }

        // MODE NORMAL (WAJIB SCAN QR)
        if (!session()->has('qr_token')) {
            abort(403, 'Silakan scan QR terlebih dahulu.');
        }

        return view('pengunjung.step1');
    }

    /**
     * ============================
     * SIMPAN STEP 1 (SESSION)
     * ============================
     */
    public function postStep1(Request $request)
    {
        // IZINKAN PREVIEW SAAT LOCAL
        if (
            !session()->has('qr_token') &&
            !(app()->environment('local') && $request->query('preview'))
        ) {
            abort(403, 'Silakan scan QR terlebih dahulu.');
        }

        $request->validate([
            'nama'     => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'tujuan'   => 'required|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        session([
            'pengunjung' => $request->only([
                'nama',
                'instansi',
                'tujuan',
                'no_hp'
            ])
        ]);

        return redirect()->route('pengunjung.survei');
    }

    /**
     * ============================
     * HALAMAN SURVEI
     * ============================
     */
    public function survei(Request $request) {
        if (
            !(app()->environment('local') && $request->query('preview')) &&
            !session()->has('pengunjung')
        ) {
            return redirect()->route('pengunjung.step1');
        }

        return view('pengunjung.survei');
    }

    /**
     * ============================
     * SUBMIT FINAL
     * ============================
     */
    public function submit(Request $request)
    {
        if (!session()->has('pengunjung') || !session()->has('qr_token')) {
            abort(403);
        }

        $request->validate([
            'kepuasan'  => 'required',
            'pelayanan' => 'required',
            'saran'     => 'required|string',
        ]);

        $data = session('pengunjung');

        Pengunjung::create([
            'nama'              => $data['nama'],
            'instansi'          => $data['instansi'] ?? null,
            'tujuan'            => $data['tujuan'],
            'no_hp'             => $data['no_hp'] ?? null,
            'ip_address'        => $request->ip(),
            'tanggal_kunjungan' => Carbon::now()->toDateString(),
        ]);

        // 1 QR = 1 pengunjung
        session()->forget([
            'pengunjung',
            'qr_token'
        ]);

        return redirect()
            ->route('pengunjung.step1')
            ->with('success', 'Terima kasih, data berhasil dikirim.');
    }

    public function storeSurvei(Request $request) {
        if (!session()->has('pengunjung')) {
        abort(403);
    }

    $request->validate([
        'kepuasan'  => 'required',
        'pelayanan' => 'required',
        'saran'     => 'required|string',
    ]);

    $data = session('pengunjung');

    Pengunjung::create([
        'nama'              => $data['nama'],
        'instansi'          => $data['instansi'] ?? null,
        'tujuan'            => $data['tujuan'],
        'no_hp'             => $data['no_hp'] ?? null,
        'ip_address'        => $request->ip(),
        'tanggal_kunjungan' => Carbon::now()->toDateString(),
    ]);

    session()->forget(['pengunjung', 'qr_token']);

    return redirect()
        ->route('pengunjung.step1')
        ->with('success', 'Terima kasih, data berhasil dikirim.');
    }
}
