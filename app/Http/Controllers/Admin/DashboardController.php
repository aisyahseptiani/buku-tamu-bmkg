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
        $filter = $request->filter ?? 'hari';

        /*
        ======================
        DEFAULT VARIABLE
        ======================
        */
        $grafik       = collect();
        $pengunjungs  = collect();
        $rekapBulanan = collect();
        $surveis      = collect();
        $rekapSurvei  = 0;
        $total        = 0;

        /*
        ======================
        MODE SURVEI
        ======================
        */
        if ($mode === 'survei') {

            $from = $request->from
                ? Carbon::parse($request->from)->startOfDay()
                : now()->startOfMonth();

            $to = $request->to
                ? Carbon::parse($request->to)->endOfDay()
                : now()->endOfMonth();

            $surveis = Survei::whereBetween('created_at', [$from, $to])
                ->latest()
                ->get();

            $rekapSurvei = Survei::whereBetween('created_at', [$from, $to])
                ->selectRaw('kepuasan, COUNT(*) as total')
                ->groupBy('kepuasan')
                ->get();

            $totalSurvei = $rekapSurvei->sum('total');

            return view('admin.dashboard', compact(
                'mode',
                'filter',
                'surveis',
                'rekapSurvei',
                'totalSurvei'
            ));
        }

        /*
        ======================
        MODE PENGUNJUNG
        ======================
        */
        if ($filter === 'tahun') {

            $tahun = $request->tahun ?? now()->year;

            $pengunjungs = Pengunjung::whereYear('created_at', $tahun)->get();

            $raw = Pengunjung::whereYear('created_at', $tahun)
                ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                ->groupByRaw('MONTH(created_at)')
                ->pluck('total', 'bulan');

            for ($i = 1; $i <= 12; $i++) {

                $jumlah = $raw[$i] ?? 0;

                $rekapBulanan->push([
                    'bulan'      => $i,
                    'nama_bulan' => Carbon::create()->month($i)->translatedFormat('F'),
                    'total'      => $jumlah
                ]);

                $grafik->push([
                    'label' => Carbon::create()->month($i)->translatedFormat('F'),
                    'total' => $jumlah
                ]);
            }

            $total = $rekapBulanan->sum('total');
        }
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
            'mode',
            'filter',
            'pengunjungs',
            'rekapBulanan',
            'surveis',
            'rekapSurvei',
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
        }
        else {

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

    public function laporanSurvei()
    {
        $config = config('survei');
        $rekap  = [];

        foreach ($config as $field => $item) {

            $data = [];

            foreach ($item['opsi'] as $value => $label) {
                $data[$label] = Survei::where($field, $value)->count();
            }

            $rekap[] = [
                'label' => $item['label'],
                'data'  => $data,
            ];
        }

        $saran = Survei::whereNotNull('saran')
            ->where('saran', '!=', '')
            ->latest()
            ->pluck('saran');

        $masukan = Survei::whereNotNull('masukan')
            ->where('masukan', '!=', '')
            ->latest()
            ->pluck('masukan');

        return view('admin.laporan-survei', compact(
            'rekap',
            'saran',
            'masukan'
        ));
    }

    public function postStep1(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'instansi' => 'nullable|string|max:255',
            'tujuan'   => 'required|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        // SIMPAN KE DATABASE (INI YANG SEBELUMNYA TIDAK ADA)
        $pengunjung = Pengunjung::create([
            'nama'       => $request->nama,
            'instansi'   => $request->instansi,
            'tujuan'     => $request->tujuan,
            'no_hp'      => $request->no_hp,
            'ip_address' => $request->ip(),
        ]);

        // SIMPAN ID KE SESSION
        session([
            'pengunjung_id' => $pengunjung->id
        ]);

        return redirect()->route('pengunjung.survei');
    }

}
