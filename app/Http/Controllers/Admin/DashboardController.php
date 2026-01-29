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

        /*
        ======================
        TENTUKAN RANGE TANGGAL
        ======================
        */
        if ($filter === 'hari') {
            $from = Carbon::today()->startOfDay();
            $to   = Carbon::today()->endOfDay();
        } else {
            $from = $request->from
                ? Carbon::parse($request->from)->startOfDay()
                : now()->startOfMonth();

            $to = $request->to
                ? Carbon::parse($request->to)->endOfDay()
                : now()->endOfMonth();
        }

        /*
        ======================
        DATA TABEL
        ======================
        */
        $pengunjungs = Pengunjung::whereBetween('created_at', [$from, $to])
            ->latest()
            ->get();

        $total = $pengunjungs->count();

        /*
        ======================
        DATA GRAFIK (RAW)
        ======================
        */
        $rawGrafik = Pengunjung::whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'tanggal');

        /*
        ======================
        ISI TANGGAL KOSONG
        ======================
        */
        $periode = CarbonPeriod::create(
            $from->toDateString(),
            $to->toDateString()
        );

        $grafik = collect();

        foreach ($periode as $date) {
            $tgl = $date->toDateString();
            $grafik->push([
                'tanggal' => $tgl,
                'total'   => $rawGrafik[$tgl] ?? 0
            ]);
        }

        return view('admin.dashboard', compact(
            'pengunjungs',
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

        if ($filter === 'hari') {
            $from = Carbon::today()->startOfDay();
            $to   = Carbon::today()->endOfDay();
        } else {
            $from = $request->from
                ? Carbon::parse($request->from)->startOfDay()
                : now()->startOfMonth();

            $to = $request->to
                ? Carbon::parse($request->to)->endOfDay()
                : now()->endOfMonth();
        }

        $pengunjungs = Pengunjung::whereBetween('created_at', [$from, $to])
            ->get();

        $pdf = Pdf::loadView(
            'admin.dashboard-pdf',
            compact('pengunjungs', 'filter', 'from', 'to')
        );

        return $pdf->download(
            'dashboard-' . $from->format('d-m-Y') .
            '_sd_' .
            $to->format('d-m-Y') . '.pdf'
        );
    }
}
