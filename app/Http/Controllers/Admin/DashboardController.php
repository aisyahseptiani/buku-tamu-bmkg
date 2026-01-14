<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $filter = $request->filter ?? 'hari';

        // =====================
        // QUERY DATA TABEL
        // =====================
        $dataQuery = Pengunjung::query();

        if ($filter == 'hari') {
            $dataQuery->whereDate('created_at', today());
        } elseif ($filter == 'minggu') {
            $dataQuery->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($filter == 'bulan') {
            $dataQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
        } elseif ($filter == 'tahun') {
            $dataQuery->whereYear('created_at', now()->year);
        }

        $pengunjungs = $dataQuery->latest()->get();
        $total = $pengunjungs->count();

        // =====================
        // QUERY GRAFIK (TERPISAH)
        // =====================
        $grafikQuery = Pengunjung::query();

        if ($filter == 'hari') {
            $grafikQuery->whereDate('created_at', today());
        } elseif ($filter == 'minggu') {
            $grafikQuery->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($filter == 'bulan') {
            $grafikQuery->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
        } elseif ($filter == 'tahun') {
            $grafikQuery->whereYear('created_at', now()->year);
        }

        $grafik = $grafikQuery
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at) ASC')
            ->get();

        return view('admin.dashboard', compact(
            'pengunjungs',
            'filter',
            'total',
            'grafik'
        ));
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->filter ?? 'hari';

        $query = Pengunjung::query();

        if ($filter == 'hari') {
            $query->whereDate('created_at', today());
        } elseif ($filter == 'minggu') {
            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        } elseif ($filter == 'bulan') {
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        } elseif ($filter == 'tahun') {
            $query->whereYear('created_at', now()->year);
        }

        $pengunjungs = $query->get();

        $pdf = Pdf::loadView(
            'admin.dashboard-pdf',
            compact('pengunjungs', 'filter')
        );

        return $pdf->download('dashboard-'.$filter.'.pdf');
    }
}
