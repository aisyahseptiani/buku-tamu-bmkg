@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    use Carbon\Carbon;
    $today = Carbon::now()->translatedFormat('l, d F Y');
    $mode  = request('mode', 'pengunjung'); // ⬅️ MODE TAMBAHAN
@endphp

{{-- ================= HEADER ================= --}}
<div class="mb-4">
    <h3 class="fw-bold">Dashboard</h3>
    <div class="text-muted">{{ $today }}</div>
</div>

{{-- ================= PILIH MODE ================= --}}
<div class="mb-3">
    <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['mode' => 'pengunjung'])) }}"
       class="btn btn-outline-primary {{ $mode === 'pengunjung' ? 'active' : '' }}">
        Data Pengunjung
    </a>

    <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['mode' => 'survei'])) }}"
       class="btn btn-outline-success {{ $mode === 'survei' ? 'active' : '' }}">
        Data Survei
    </a>
</div>

{{-- ================= FILTER ================= --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET">
            <input type="hidden" name="mode" value="{{ $mode }}"> {{-- ⬅️ JAGA MODE --}}

            <div class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Jenis Filter</label>
                    <select name="filter" class="form-select form-select-sm">
                        <option value="hari" {{ request('filter')=='hari'?'selected':'' }}>Hari Ini</option>
                        <option value="minggu" {{ request('filter')=='minggu'?'selected':'' }}>Mingguan</option>
                        <option value="bulan" {{ request('filter')=='bulan'?'selected':'' }}>Bulanan</option>
                        <option value="tahun" {{ request('filter')=='tahun'?'selected':'' }}>Tahunan</option>
                    </select>
                </div>

                @if(request('filter') !== 'hari')
                <div class="col-md-5">
                    <label class="form-label small fw-semibold mb-1">Rentang Tanggal</label>
                    <div class="input-group input-group-sm">
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                        <span class="input-group-text">—</span>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                </div>
                @endif

                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100 fw-semibold">
                        Tampilkan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- ================= SWITCH TABEL / GRAFIK ================= --}}
@if($mode === 'pengunjung')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group btn-group-sm">
        <button type="button" class="btn btn-outline-primary active" id="btnTabel" onclick="showTabel()">
            Tabel
        </button>
        <button type="button" class="btn btn-outline-primary" id="btnGrafik" onclick="showGrafik()">
            Grafik
        </button>
    </div>
</div>
@endif

{{-- ================= TABEL ================= --}}
<div class="card shadow-sm mb-4" id="sectionTabel">
    <div class="card-body">

        {{-- ================= DATA PENGUNJUNG (LAMA, UTUH) ================= --}}
        @if($mode === 'pengunjung')

        <h5 class="mb-3">Data Pengunjung</h5>

        @if($filter === 'tahun')
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Total Pengunjung</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grafik as $row)
                        <tr>
                            <td>{{ $row['label'] }}</td>
                            <td>{{ $row['total'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="fw-bold table-secondary">
                        <td>Total</td>
                        <td>{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        @else
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Instansi</th>
                            <th>Tujuan</th>
                            <th>No HP</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengunjungs as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->instansi }}</td>
                                <td>{{ $p->tujuan }}</td>
                                <td>{{ $p->no_hp }}</td>
                                <td>{{ $p->created_at->translatedFormat('d F Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        @endif

        {{-- ================= DATA SURVEI (TAMBAHAN) ================= --}}
        @if($mode === 'survei')

        {{-- ================= REKAP SURVEI ================= --}}
        @if(isset($rekapSurvei))
        <hr class="my-4">

        <h5 class="mb-4 fw-bold">
            Rekapitulasi Hasil Survei Kepuasan
        </h5>

        <div class="row">
        @foreach($rekapSurvei as $item)
            @php
                $totalResponden = array_sum($item['data']);
            @endphp

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">

                        <h6 class="fw-semibold mb-3">
                            {{ $item['label'] }}
                        </h6>

                        @foreach($item['data'] as $opsi => $jumlah)
                            @php
                                $persen = $totalResponden > 0
                                    ? round(($jumlah / $totalResponden) * 100)
                                    : 0;
                            @endphp

                            <div class="mb-2">
                                <div class="d-flex justify-content-between small">
                                    <span>{{ $opsi }}</span>
                                    <span class="fw-semibold">
                                        {{ $jumlah }} ({{ $persen }}%)
                                    </span>
                                </div>

                                <div class="progress" style="height:6px">
                                    <div class="progress-bar bg-primary"
                                        style="width: {{ $persen }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-muted small mt-2">
                            Total responden: {{ $totalResponden }}
                        </div>

                    </div>
                </div>
            </div>
        @endforeach
        </div>
        @endif


        <!-- <h5 class="mb-3">Data Survei Kepuasan Pengunjung</h5>

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Saran</th>
                    <th>Masukan</th>
                    <th>Detail Jawaban</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surveis as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->created_at->translatedFormat('d F Y H:i') }}</td>
                        <td>{{ $s->saran ?? '-' }}</td>
                        <td>{{ $s->masukan ?? '-' }}</td>
                        <td>
                            <button class="btn btn-sm btn-info"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#detail{{ $s->id }}">
                                Lihat
                            </button>
                        </td>
                    </tr>

                    <tr class="collapse" id="detail{{ $s->id }}">
                        <td colspan="5">
                            <ul class="mb-0">
                                @foreach($s->detail as $d)
                                    <li>
                                        <strong>{{ $d->pertanyaan }}:</strong>
                                        {{ $d->jawaban }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Tidak ada data survei
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table> -->

        @endif

    </div>
</div>

<div class="d-flex justify-content-end mt-3">
    <a href="{{ route('admin.dashboard.exportPdf', request()->query()) }}"
       class="btn btn-danger">
        Download PDF
    </a>
</div>

{{-- ================= GRAFIK (PENGUNJUNG SAJA) ================= --}}
@if($mode === 'pengunjung')
<div class="card shadow-sm d-none" id="sectionGrafik">
    <div class="card-body">
        <h5 class="mb-3">Grafik Pengunjung</h5>
        <div style="height:360px">
            <canvas id="grafikPengunjung"></canvas>
        </div>
    </div>
</div>
@endif

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if($mode === 'pengunjung')
<script>
const labels = {!! json_encode($grafik->pluck('label')) !!};
const data   = {!! json_encode($grafik->pluck('total')) !!};

const chart = new Chart(document.getElementById('grafikPengunjung'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: '#4e73df',
            borderRadius: 6,
            barThickness: 24
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0, stepSize: 1 } },
            x: { grid: { display: false } }
        }
    }
});

function showTabel() {
    sectionTabel.classList.remove('d-none');
    sectionGrafik.classList.add('d-none');
    btnTabel.classList.add('active');
    btnGrafik.classList.remove('active');
}

function showGrafik() {
    sectionGrafik.classList.remove('d-none');
    sectionTabel.classList.add('d-none');
    btnGrafik.classList.add('active');
    btnTabel.classList.remove('active');
}
</script>
@endif
@endpush
