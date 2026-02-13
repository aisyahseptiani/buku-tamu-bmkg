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
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Dashboard</h3>
        <div class="text-muted">{{ $today }}</div>
    </div>
</div>

{{-- MODE SWITCH TAB --}}
<div class="mb-4">
    <ul class="nav nav-tabs">

        <li class="nav-item">
            <a class="nav-link {{ $mode==='pengunjung' ? 'active fw-semibold' : '' }}"
               href="{{ route('admin.dashboard', array_merge(request()->query(), ['mode'=>'pengunjung'])) }}">
                <i class="bi bi-people me-1"></i>
                Data Pengunjung
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ $mode==='survei' ? 'active fw-semibold' : '' }}"
               href="{{ route('admin.dashboard', array_merge(request()->query(), ['mode'=>'survei'])) }}">
                <i class="bi bi-clipboard-check me-1"></i>
                Data Survei
            </a>
        </li>

    </ul>
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

{{-- ================= TABEL ================= --}}
<div class="card shadow-sm mb-4" id="sectionTabel">
    <div class="card-body">

@if($mode === 'pengunjung')

<!-- tampilkan periode -->
<div class="d-flex align-items-center mb-2">
    <span class="text-muted">
        <strong>Data Pengunjung Periode : {{ $periodeText }}</strong>
    </span>
</div>


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
    @foreach($rekapSurvei as $soal)

    @php
        $total = collect($soal['opsi'])->sum('total');
        $max = collect($soal['opsi'])->max('total');
    @endphp

    <div class="card border-0 shadow-sm mb-4 survey-card">
        <div class="card-body p-4">

            <div class="mb-4">
                <h6 class="fw-bold mb-1">
                    {{ $soal['pertanyaan'] }}
                </h6>
                <small class="text-muted">
                    {{ $total }} Total Responden
                </small>
            </div>

            @foreach($soal['opsi'] as $opsi)

                @php
                    $persen = $total > 0 ? round(($opsi['total'] / $total) * 100) : 0;
                    $isTop = $opsi['total'] == $max && $max > 0;
                @endphp

                <div class="ranking-item position-relative mb-2 {{ $isTop ? 'top-item' : '' }}">

                    <div class="ranking-bar"
                        style="width: {{ $persen }}%;">
                    </div>

                    <div class="ranking-content d-flex justify-content-between align-items-center">

                        <div>
                            <div class="option-label">
                                {{ $opsi['label'] }}

                                @if($isTop)
                                    <span class="badge-top ms-2">
                                        Terbanyak
                                    </span>
                                @endif
                            </div>

                            <small class="text-muted">
                                {{ $opsi['total'] }} respon
                            </small>
                        </div>

                        <div class="ranking-percent">
                            {{ $persen }}%
                        </div>

                    </div>

                </div>

            @endforeach

        </div>
    </div>

    @endforeach

<style>

.survey-card {
    border-radius: 14px;
    background: #ffffff;
}

.ranking-item {
    background: #f9fafb;
    border-radius: 8px;
    padding: 8px 14px;   /* 🔥 lebih kecil */
    min-height: 48px;    /* 🔥 lebih pendek */
    overflow: hidden;
    transition: background 0.2s ease;
}

.ranking-item:hover {
    background: #f1f5f9;
}

.ranking-bar {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    background: linear-gradient(90deg, #2563eb, #60a5fa);
    opacity: 0.10;
    transition: width 0.8s ease;
}

.ranking-content {
    position: relative;
    z-index: 2;
}

.option-label {
    font-size: 13px;   /* 🔥 lebih kecil */
    font-weight: 600;
    color: #1f2937;
}

.ranking-percent {
    font-size: 14px;   /* 🔥 lebih kecil */
    font-weight: 700;
    color: #1f2937;
}

.badge-top {
    font-size: 10px;   /* 🔥 lebih kecil */
    background: #2563eb;
    color: #ffffff;
    padding: 2px 6px;
    border-radius: 14px;
}

.top-item {
    background: #eef2ff;
}

</style>




<div class="mt-4">
    <div class="bg-success bg-opacity-10 text-success px-4 py-3 rounded-top
                border-top border-3 border-success
                d-flex justify-content-between align-items-center">

        <span>
            <i class="bi bi-people-fill me-1"></i>
            Total Responden
        </span>

        <span class="fw-bold fs-5">
            {{ $totalResponden }}
        </span>

    </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    @foreach($rekapSurvei ?? [] as $index => $soal)

    let labels{{ $index }} = {!! json_encode(array_column($soal['opsi'], 'label')) !!};
    let data{{ $index }}   = {!! json_encode(array_column($soal['opsi'], 'total')) !!};

    let totalRespon{{ $index }} = data{{ $index }}.reduce((a,b)=>a+b,0);

    new Chart(document.getElementById('chart{{ $index }}'), {
        type: 'bar',
        data: {
            labels: labels{{ $index }},
            datasets: [{
                data: data{{ $index }},
                backgroundColor: [
                    '#198754',
                    '#20c997',
                    '#0dcaf0',
                    '#ffc107',
                    '#dc3545'
                ],
                borderRadius: 8,
                barThickness: 22,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            animation: {
                duration: 1200
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            let percent = totalRespon{{ $index }} 
                                ? ((value / totalRespon{{ $index }}) * 100).toFixed(1)
                                : 0;
                            return value + " respon (" + percent + "%)";
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: {
                        color: "#e9ecef"
                    }
                },
                y: {
                    grid: { display: false }
                }
            }
        }
    });

    @endforeach

});
</script>
@endsection
