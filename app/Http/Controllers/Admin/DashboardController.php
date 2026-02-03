<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengunjung;
use App\Models\Survei;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mode   = $request->get('mode', 'pengunjung');
        $filter = $request->get('filter', 'hari');

        /*
        ======================
        TENTUKAN RENTANG WAKTU
        ======================
        */
        if ($filter === 'hari') {
            $from = now()->startOfDay();
            $to   = now()->endOfDay();
        } elseif ($filter === 'bulan') {
            $from = now()->startOfMonth();
            $to   = now()->endOfMonth();
        } else { // tahun
            $from = now()->startOfYear();
            $to   = now()->endOfYear();
        }
        /*
        ======================
        TEKS PERIODE
        ======================
        */
        if ($filter === 'hari') {
            $periodeText = $from->translatedFormat('l, d F Y');
        } elseif ($filter === 'bulan') {
            $periodeText = $from->translatedFormat('F Y');
        } else {
            $periodeText = 'Tahun ' . $from->translatedFormat('Y');
        }


        /*
        ======================
        DEFAULT VARIABLE
        ======================
        */
        $pengunjungs    = collect();
        $grafik         = collect();
        $rekapSurvei    = [];
        $total          = 0;
        $totalResponden = 0;

        /*
        ======================
        MODE SURVEI
        ======================
        */
        if ($mode === 'survei') {

            $surveis = Survei::whereBetween('created_at', [$from, $to])->get();
            $totalResponden = $surveis->count();

            $config = config('survei');

            foreach ($config as $field => $item) {

                $opsiData = [];

                foreach ($item['opsi'] as $label) {
                    $jumlah = $surveis->filter(function ($row) use ($field, $label) {
                        return isset($row->jawaban[$field]) &&
                               $row->jawaban[$field] === $label;
                    })->count();

                    $opsiData[] = [
                        'label' => $label,
                        'total' => $jumlah,
                    ];
                }

                $rekapSurvei[] = [
                    'pertanyaan' => $item['label'],
                    'opsi'       => $opsiData,
                ];
            }

            return view('admin.dashboard', compact(
                'mode',
                'filter',
                'rekapSurvei',
                'totalResponden',
                'grafik',
                'periodeText' 
            ));
        }

        /*
        ======================
        MODE PENGUNJUNG
        ======================
        */
        $pengunjungs = Pengunjung::whereBetween('created_at', [$from, $to])->get();
        $total = $pengunjungs->count();

        return view('admin.dashboard', compact(
            'mode',
            'filter',
            'pengunjungs',
            'grafik',
            'total',
            'periodeText'
        ));
    }

    /*
    ======================
    EXPORT PDF
    ======================
    */
    public function exportPdf(Request $request)
    {
        $filter = $request->get('filter', 'hari');

        if ($filter === 'hari') {
            $from = now()->startOfDay();
            $to   = now()->endOfDay();
        } elseif ($filter === 'bulan') {
            $from = now()->startOfMonth();
            $to   = now()->endOfMonth();
        } else {
            $from = now()->startOfYear();
            $to   = now()->endOfYear();
        }

        $pengunjungs = Pengunjung::whereBetween('created_at', [$from, $to])->get();

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
