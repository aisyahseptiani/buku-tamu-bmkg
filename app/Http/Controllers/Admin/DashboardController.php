<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter ?? 'hari';

        $grafik = collect();
        $pengunjungs = collect();
        $rekapBulanan = collect();
        $total = 0;

        /*
        ======================
        FILTER TAHUNAN
        ======================
        */
        if ($filter === 'tahun') {

            $tahun = $request->tahun ?? now()->year;

            // DATA ASLI (UNTUK PDF & REFERENSI)
            $pengunjungs = Pengunjung::whereYear('created_at', $tahun)->get();

            // REKAP PER BULAN
            $raw = Pengunjung::whereYear('created_at', $tahun)
                ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->groupByRaw('MONTH(created_at)')
                ->pluck('total', 'bulan');

            for ($i = 1; $i <= 12; $i++) {

                $jumlah = $raw[$i] ?? 0;

                $rekapBulanan->push([
                    'bulan' => $i,
                    'nama_bulan' => Carbon::create()->month($i)->translatedFormat('F'),
                    'total' => $jumlah
                ]);

                $grafik->push([
                    'label' => Carbon::create()->month($i)->translatedFormat('F'),
                    'total' => $jumlah
                ]);
            }

            $total = $rekapBulanan->sum('total');
        }

        /*
        ======================
        FILTER HARI / RANGE
        ======================
        */
        else {

            $from = $request->from
                ? Carbon::parse($request->from)->startOfDay()
                : now()->startOfMonth();

            $to = $request->to
                ? Carbon::parse($request->to)->endOfDay()
                : now()->endOfMonth();

            $pengunjungs = Pengunjung::whereBetween('created_at', [$from, $to])
                ->latest()
                ->get();

            $total = $pengunjungs->count();

            $rawGrafik = Pengunjung::whereBetween('created_at', [$from, $to])
                ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
                ->groupByRaw('DATE(created_at)')
                ->pluck('total', 'tanggal');

            $periode = CarbonPeriod::create($from, $to);

            foreach ($periode as $date) {
                $tgl = $date->toDateString();

                $grafik->push([
                    'label' => $date->translatedFormat('d M'),
                    'total' => $rawGrafik[$tgl] ?? 0
                ]);
            }
        }

        return view('admin.dashboard', compact(
            'pengunjungs',
            'rekapBulanan',
            'filter',
            'total',
            'grafik'
        ));
    }

    /*
    ======================
    EXPORT PDF
    ======================
    */
    public function exportPdf(Request $request)
    {
        $filter = $request->filter ?? 'hari';

        if ($filter === 'tahun') {

            $tahun = $request->tahun ?? now()->year;

            $pengunjungs = Pengunjung::whereYear('created_at', $tahun)->get();

            $from = Carbon::create($tahun, 1, 1);
            $to   = Carbon::create($tahun, 12, 31);

        } else {

            $from = $request->from
                ? Carbon::parse($request->from)->startOfDay()
                : now()->startOfMonth();

            $to = $request->to
                ? Carbon::parse($request->to)->endOfDay()
                : now()->endOfMonth();

            $pengunjungs = Pengunjung::whereBetween('created_at', [$from, $to])->get();
        }

        $pdf = Pdf::loadView(
            'admin.dashboard-pdf',
            compact('pengunjungs', 'filter', 'from', 'to')
        );

        return $pdf->download(
            'dashboard-' .
            $from->format('d-m-Y') .
            '_sd_' .
            $to->format('d-m-Y') .
            '.pdf'
        );
    }
}
