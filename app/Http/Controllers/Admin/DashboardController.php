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

        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);


        /*
        ======================
        TENTUKAN RENTANG WAKTU
        ======================
        */
        if ($filter === 'hari') {

            $from = now()->startOfDay();
            $to   = now()->endOfDay();

        } elseif ($filter === 'bulan') {

            $from = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $to   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        } else { // tahun

            $from = Carbon::create($tahun, 1, 1)->startOfYear();
            $to   = Carbon::create($tahun, 12, 31)->endOfYear();
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

        // WAJIB selalu ada
        $grafik = collect();
        $rekapSurvei = [];
        $totalResponden = 0;

        if ($filter === 'tahun') {

            $grafik = Pengunjung::selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->whereBetween('created_at', [$from, $to])
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get()
                ->map(function ($item) {
                    return [
                        'label' => \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F'),
                        'total' => $item->total
                    ];
                });
        }

        return view('admin.dashboard', compact(
            'mode',
            'filter',
            'pengunjungs',
            'grafik',
            'total',
            'periodeText',
            'rekapSurvei',
            'totalResponden'
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
        $bulan  = $request->get('bulan', now()->month);
        $tahun  = $request->get('tahun', now()->year);

        if ($filter === 'hari') {

            $from = now()->startOfDay();
            $to   = now()->endOfDay();
            $periodeText = $from->translatedFormat('l, d F Y');

        } elseif ($filter === 'bulan') {

            $from = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $to   = Carbon::create($tahun, $bulan, 1)->endOfMonth();
            $periodeText = $from->translatedFormat('F Y');

        } else { // tahun

            $from = Carbon::create($tahun, 1, 1)->startOfYear();
            $to   = Carbon::create($tahun, 12, 31)->endOfYear();
            $periodeText = 'Tahun ' . $from->translatedFormat('Y');
        }

        $pengunjungs = Pengunjung::whereBetween('created_at', [$from, $to])->get();

        $pdf = Pdf::loadView(
            'admin.dashboard-pdf',
            compact('pengunjungs', 'periodeText')
        );
        if ($filter === 'hari') {

            $namaFile = 'Laporan Data Pengunjung_' . $from->format('d-m-Y');

        } elseif ($filter === 'bulan') {

            $namaBulan = $from->translatedFormat('F');
            $namaFile = 'Laporan Data Pengunjung_' . $namaBulan . '_' . $tahun;

        } else {

            $namaFile = 'Laporan Data Pengunjung_Tahun_' . $tahun;
        }

        $namaFile = str_replace(' ', '_', $namaFile) . '.pdf';

        return $pdf->download($namaFile);
    }

    public function downloadSurvei(Request $request)
    {
        Carbon::setLocale('id');

        $filter = $request->get('filter', 'hari');
        $bulan  = $request->get('bulan', now()->month);
        $tahun  = $request->get('tahun', now()->year);

        // Tentukan rentang waktu (sama seperti index)
        if ($filter === 'hari') {
            $from = now()->startOfDay();
            $to   = now()->endOfDay();
            $periodeText = $from->translatedFormat('l, d F Y');
        } elseif ($filter === 'bulan') {
            $from = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $to   = Carbon::create($tahun, $bulan, 1)->endOfMonth();
            $periodeText = $from->translatedFormat('F Y');
        } else {
            $from = Carbon::create($tahun, 1, 1)->startOfYear();
            $to   = Carbon::create($tahun, 12, 31)->endOfYear();
            $periodeText = 'Tahun ' . $from->translatedFormat('Y');
        }

        $surveis = Survei::whereBetween('created_at', [$from, $to])->get();
        $totalResponden = $surveis->count();
        $config = config('survei');

        $rekapSurvei = [];

        foreach ($config as $field => $item) {
            $opsiData = [];

            foreach ($item['opsi'] as $label) {
                $jumlah = $surveis->filter(function ($row) use ($field, $label) {
                    return isset($row->jawaban[$field]) &&
                        $row->jawaban[$field] === $label;
                })->count();

                $persen = $totalResponden > 0
                    ? round(($jumlah / $totalResponden) * 100, 1)
                    : 0;

                $opsiData[] = [
                    'label' => $label,
                    'total' => $jumlah,
                    'persen'=> $persen
                ];
            }

            $rekapSurvei[] = [
                'pertanyaan' => $item['label'],
                'opsi'       => $opsiData,
            ];
        }

        $pdf = Pdf::loadView('admin.pdf_survei', compact(
            'rekapSurvei',
            'periodeText',
            'totalResponden'
        ))->setPaper('A4', 'portrait');

        if ($filter === 'hari') {

            $namaFile = 'Laporan_Survei_' . $from->format('d-m-Y');

        } elseif ($filter === 'bulan') {

            $namaBulan = $from->translatedFormat('F');
            $namaFile = 'Laporan_Survei_' . $namaBulan . '_' . $tahun;

        } else {

            $namaFile = 'Laporan_Survei_Tahun_' . $tahun;
        }

        // Hilangkan spasi biar aman
        $namaFile = str_replace(' ', '_', $namaFile) . '.pdf';

        return $pdf->download($namaFile);

        return $pdf->download('Laporan_Survei_BMKG.pdf');
    }


}
