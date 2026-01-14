<?php

//namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengunjung;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function exportPdf(Request $request)
    {
        // 1️⃣ ambil tanggal dari filter
        $tanggalMulai = $request->tanggal_mulai;
        $tanggalAkhir = $request->tanggal_akhir;

        // 2️⃣ query data sesuai filter
        if ($tanggalMulai && $tanggalAkhir) {
            $pengunjungs = Pengunjung::whereBetween('created_at', [
                $tanggalMulai,
                $tanggalAkhir
            ])->get();
        } else {
            $pengunjungs = Pengunjung::all();
        }

        // 3️⃣ kirim SEMUA ke view PDF
        return Pdf::loadView('admin.laporan.pdf', compact(
            'pengunjungs',
            'tanggalMulai',
            'tanggalAkhir'
        ))->download('laporan-pengunjung.pdf');

        return $pdf->download('laporan-pengunjung.pdf');
    }
}
