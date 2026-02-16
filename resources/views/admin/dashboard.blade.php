@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    use Carbon\Carbon;
    $today  = Carbon::now()->translatedFormat('l, d F Y');
    $mode   = request('mode', 'pengunjung');
    $filter = request('filter', 'hari');
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

{{-- ===================== --}}
{{-- SUMMARY SECTION --}}
{{-- ===================== --}}
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-body p-4 d-flex justify-content-between align-items-center">

        {{-- LEFT --}}
        <div>
            <div class="text-muted small mb-1">
                Total Responden
            </div>
            <div class="fs-2 fw-bold text-primary">
                {{ $totalResponden }}
            </div>
        </div>

        {{-- RIGHT --}}
        <div class="d-flex align-items-center gap-3">

            <a href="{{ route('admin.survei.download', [
                'filter' => $filter,
                'bulan'  => request('bulan'),
                'tahun'  => request('tahun')
            ]) }}" 
            class="btn btn-outline-primary rounded-pill px-4">
                <i class="bi bi-download me-1"></i>
                Download PDF
            </a>

        </div>

    </div>
</div>


{{-- ===================== --}}
{{-- LOOP PERTANYAAN --}}
{{-- ===================== --}}
@foreach($rekapSurvei as $index => $soal)

@php
    $labels = collect($soal['opsi'])->pluck('label');
    $data = collect($soal['opsi'])->pluck('total');
    $max = $data->max();
@endphp

<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">

        <h6 class="fw-semibold mb-4">
            {{ $soal['pertanyaan'] }}
        </h6>

        <canvas id="chart{{ $index }}" height="120"></canvas>

        @php
            $dominant = collect($soal['opsi'])->firstWhere('total', $max);
        @endphp

        @if($dominant && $max > 0)
            <div class="mt-3 small text-muted">
                Mayoritas responden memilih 
                <span class="fw-semibold text-primary">
                    {{ $dominant['label'] }}
                </span>
                ({{ round(($max / $data->sum()) * 100) }}%)
            </div>
        @endif

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const ctx{{ $index }} = document.getElementById('chart{{ $index }}');

    new Chart(ctx{{ $index }}, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                data: {!! json_encode($data) !!},
                backgroundColor: {!! json_encode(
                    collect($soal['opsi'])->map(function($o) use ($max){
                        return $o['total'] == $max 
                            ? 'rgba(13,110,253,0.8)' 
                            : 'rgba(108,117,125,0.4)';
                    })
                ) !!},
                borderRadius: 8,
                barThickness: 18
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.05)'
                    }
                },
                y: {
                    grid: { display: false }
                }
            }
        }

    });

});
</script>

@endforeach

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
