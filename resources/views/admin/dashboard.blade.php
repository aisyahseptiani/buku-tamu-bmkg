@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    use Carbon\Carbon;
    $today  = Carbon::now()->translatedFormat('l, d F Y');
    $mode   = request('mode', 'pengunjung');
    $filter = request('filter', 'hari');
@endphp

{{-- ================= HEADER ================= --}}
<div class="mb-4">
    <h3 class="fw-bold">Dashboard</h3>
    <div class="text-muted">{{ $today }}</div>
</div>

{{-- ================= PILIH MODE ================= --}}
<div class="mb-3">
    <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['mode'=>'pengunjung'])) }}"
       class="btn btn-outline-primary {{ $mode==='pengunjung'?'active':'' }}">
        Data Pengunjung
    </a>

    <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['mode'=>'survei'])) }}"
       class="btn btn-outline-success {{ $mode==='survei'?'active':'' }}">
        Data Survei
    </a>
</div>

{{-- ================= FILTER ================= --}}
<div class="card shadow-sm mb-4 border-0">
    <div class="card-body">
        <form method="GET">
            <input type="hidden" name="mode" value="{{ $mode }}">

            <div class="row g-3 align-items-end">

                {{-- JENIS FILTER --}}
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">
                        Jenis Filter
                    </label>
                    <select name="filter"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()">
                        <option value="hari" {{ $filter=='hari'?'selected':'' }}>
                            📅 Hari Ini
                        </option>
                        <option value="bulan" {{ $filter=='bulan'?'selected':'' }}>
                            🗓️ Bulanan
                        </option>
                        <option value="tahun" {{ $filter=='tahun'?'selected':'' }}>
                            📆 Tahunan
                        </option>
                    </select>
                </div>

                {{-- BULAN --}}
                @if($filter === 'bulan')
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">
                        Bulan
                    </label>
                    <select name="bulan" class="form-select form-select-sm">
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}"
                                {{ request('bulan', now()->month)==$b?'selected':'' }}>
                                {{ \Carbon\Carbon::create()->month($b)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- TAHUN --}}
                @if(in_array($filter, ['bulan','tahun']))
                <div class="col-md-2">
                    <label class="form-label small fw-semibold text-secondary">
                        Tahun
                    </label>
                    <input type="number"
                           name="tahun"
                           class="form-control form-control-sm"
                           value="{{ request('tahun', now()->year) }}">
                </div>
                @endif

                {{-- TOMBOL --}}
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100 fw-semibold shadow-sm">
                        <i class="bi bi-funnel"></i> Tampilkan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>


{{-- ================= SWITCH ================= --}}
@if($mode === 'pengunjung')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary active" onclick="showTabel()">Tabel</button>
        <button class="btn btn-outline-primary" onclick="showGrafik()">Grafik</button>
    </div>
</div>
@endif

{{-- ================= TABEL ================= --}}
<div class="card shadow-sm mb-4" id="sectionTabel">
    <div class="card-body">

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

<div class="d-flex justify-content-end mt-3">
    <a href="{{ route('admin.dashboard.exportPdf', request()->query()) }}" class="btn btn-danger">
        Download PDF
    </a>
</div>

@endif

{{-- ================= DATA SURVEI ================= --}}
@if($mode === 'survei' && !empty($rekapSurvei))
<hr class="my-4">
<h5 class="mb-4 fw-bold">Rekapitulasi Hasil Survei</h5>

<div class="row">
@foreach($rekapSurvei as $soal)
<div class="col-md-6 mb-4">
    <div class="card shadow-sm h-100">
        <div class="card-body">
            <h6 class="fw-bold mb-3">{{ $soal['pertanyaan'] }}</h6>

            @foreach($soal['opsi'] as $opsi)
            <div class="mb-2">
                <div class="d-flex justify-content-between small">
                    <span>{{ $opsi['label'] }}</span>
                    <span>{{ $opsi['total'] }}</span>
                </div>
                <div class="progress" style="height:6px">
                    <div class="progress-bar bg-success"
                         style="width:{{ $totalResponden ? ($opsi['total']/$totalResponden)*100 : 0 }}%">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach
</div>

<div class="alert alert-info text-center">
    <strong>Total Responden:</strong> {{ $totalResponden }}
</div>
@endif

    </div>
</div>

{{-- ================= GRAFIK ================= --}}
@if($mode === 'pengunjung')
<div class="card shadow-sm d-none" id="sectionGrafik">
    <div class="card-body" style="height:380px">
        <h5 class="mb-3">Grafik Pengunjung</h5>
        <canvas id="grafikPengunjung"></canvas>
    </div>
</div>
@endif

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@if($mode === 'pengunjung')
<script>
let grafikInstance = null;

function showTabel(){
    document.getElementById('sectionTabel').classList.remove('d-none');
    document.getElementById('sectionGrafik').classList.add('d-none');
}

function showGrafik(){
    document.getElementById('sectionGrafik').classList.remove('d-none');
    document.getElementById('sectionTabel').classList.add('d-none');

    if (!grafikInstance) renderGrafik();
}

function renderGrafik(){
    const ctx = document.getElementById('grafikPengunjung').getContext('2d');
    const dataGrafik = @json($grafik);

    grafikInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dataGrafik.map(d => d.label),
            datasets: [{
                label: 'Jumlah Pengunjung',
                data: dataGrafik.map(d => d.total),
                backgroundColor: '#0d6efd',
                borderRadius: 6,
                barPercentage: 0.6,
                categoryPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
}
</script>
@endif
@endpush
