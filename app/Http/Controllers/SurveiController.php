<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survei;

class SurveiController extends Controller
{
    public function store(Request $request)
    {
        if (!session()->has('pengunjung_id')) {
            return redirect()->route('pengunjung.step1');
        }

        $request->validate([
            'kepuasan'  => 'required',
            'pelayanan' => 'required',
            'fasilitas' => 'required',
            'saran'     => 'nullable',
            'masukan'   => 'nullable',
        ]);

        Survei::create([
            'pengunjung_id' => session('pengunjung_id'),
            'kepuasan'      => $request->kepuasan,
            'pelayanan'     => $request->pelayanan,
            'fasilitas'     => $request->fasilitas,
            'saran'         => $request->saran,
            'masukan'       => $request->masukan,
        ]);

        session()->forget('pengunjung_id');

        return redirect()
            ->route('pengunjung.step1')
            ->with('success', 'Terima kasih atas surveinya');
    }
}
