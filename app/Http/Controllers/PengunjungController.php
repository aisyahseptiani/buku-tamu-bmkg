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
     * SIMPAN STEP 1 KE SESSION
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
    public function survei(Request $request)
    {
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
     * SUBMIT FINAL (SIMPAN DB)
     * ============================
     */
    public function storeSurvei(Request $request)
    {
        if (!session()->has('pengunjung')) {
        abort(403, 'Session pengunjung tidak ditemukan');
        }

        $request->validate([
            'kepuasan'  => 'required',
            'pelayanan' => 'required',
            'fasilitas' => 'required',
            'saran'     => 'nullable|string',
            'masukan'   => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            $data = session('pengunjung');

            // simpan pengunjung
            $pengunjung = Pengunjung::create([
                'nama'              => $data['nama'],
                'instansi'          => $data['instansi'] ?? null,
                'tujuan'            => $data['tujuan'],
                'no_hp'             => $data['no_hp'] ?? null,
                'ip_address'        => request()->ip(),
                'tanggal_kunjungan' => now()->toDateString(),
            ]);

            // simpan survei
            Survei::create([
                'pengunjung_id' => $pengunjung->id,
                'kepuasan'      => $request->kepuasan,
                'pelayanan'     => $request->pelayanan,
                'fasilitas'     => $request->fasilitas,
                'saran'         => $request->saran,
                'masukan'       => $request->masukan,
            ]);
        });

        session()->forget(['pengunjung', 'qr_token']);

        return redirect()
            ->route('pengunjung.step1')
            ->with('success', 'Terima kasih atas surveinya');
    }
}
