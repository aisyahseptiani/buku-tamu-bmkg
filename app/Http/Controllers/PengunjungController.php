<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengunjung;
use App\Models\Survei;

class PengunjungController extends Controller
{
    /* =========================
     * HELPER PREVIEW (LOCAL)
     * ========================= */
    private function isPreview(Request $request): bool
    {
        return app()->environment('local') && $request->query('preview');
    }

    /* =========================
     * STEP 1 - FORM BIODATA
     * ========================= */
    public function step1(Request $request)
    {
        if ($this->isPreview($request)) {
            session(['qr_token' => 'PREVIEW']);
            return view('pengunjung.step1');
        }

        if (!session()->has('qr_token')) {
            abort(403, 'Silakan scan QR terlebih dahulu.');
        }

        return view('pengunjung.step1');
    }

    /* =========================
     * SIMPAN BIODATA KE SESSION
     * ========================= */
    public function postStep1(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'tujuan'   => 'required|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        session([
            'pengunjung' => $request->only([
                'nama', 'instansi', 'tujuan', 'no_hp'
            ])
        ]);

        return redirect()->route('pengunjung.survei', request()->query());
    }

    /* =========================
     * HALAMAN SURVEI
     * ========================= */
    public function survei(Request $request)
    {
        if (!$this->isPreview($request) && !session()->has('pengunjung')) {
            return redirect()->route('pengunjung.step1');
        }

        return view('pengunjung.survei');
    }

    /* =========================
     * SUBMIT SURVEI (FINAL)
     * ========================= */
    public function storeSurvei(Request $request)
    {
        

        if (!$this->isPreview($request) && !session()->has('pengunjung')) {
            abort(403, 'Silakan scan QR terlebih dahulu.');
        }

        $request->validate([
            'jawaban' => 'required|array',
            'saran'   => 'nullable|string',
            'masukan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {

            // ===== PREVIEW MODE =====
            if ($this->isPreview($request)) {
                $pengunjung = Pengunjung::create([
                    'nama'       => 'PREVIEW USER',
                    'tujuan'     => 'Preview',
                    'ip_address' => request()->ip(),
                ]);
            }
            // ===== PRODUCTION MODE =====
            else {
                $data = session('pengunjung');

                $pengunjung = Pengunjung::create([
                    'nama'       => $data['nama'],
                    'instansi'   => $data['instansi'] ?? null,
                    'tujuan'     => $data['tujuan'],
                    'no_hp'      => $data['no_hp'] ?? null,
                    'ip_address' => request()->ip(),
                ]);
            }

            Survei::create([
                'pengunjung_id' => $pengunjung->id,
                'jawaban'       => $request->jawaban,
                'saran'         => $request->saran,
                'masukan'       => $request->masukan,
            ]);
        });

        session()->forget(['pengunjung', 'qr_token']);

        return redirect()
            ->route('pengunjung.step1', ['preview' => 1])
            ->with('success', 'Terima kasih atas surveinya');

       

    }
}
