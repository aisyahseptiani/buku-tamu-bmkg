@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    use Carbon\Carbon;
    $today = Carbon::now()->translatedFormat('l, d F Y');
    $mode  = request('mode', 'pengunjung');
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
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET">
            <input type="hidden" name="mode" value="{{ $mode }}">

            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Jenis Filter</label>
                    <select name="filter" class="form-select form-select-sm">
                        <option value="hari" {{ request('filter')=='hari'?'selected':'' }}>Hari Ini</option>
                        <option value="minggu" {{ request('filter')=='minggu'?'selected':'' }}>Mingguan</option>
                        <option value="bulan" {{ request('filter')=='bulan'?'selected':'' }}>Bulanan</option>
                        <option value="tahun" {{ request('filter')=='tahun'?'selected':'' }}>Tahunan</option>
                    </select>
                </div>

                @if(request('filter') !== 'hari')
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Rentang Tanggal</label>
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
        <button class="btn btn-outline-primary active" id="btnTabel" onclick="showTabel()">Tabel</button>
        <button class="btn btn-outline-primary" id="btnGrafik" onclick="showGrafik()">Grafik</button>
    </div>
</div>
@endif

{{-- ================= TABEL ================= --}}
<div class="card shadow-sm mb-4" id="sectionTabel">
    <div class="card-body">

{{-- ================= DATA PENGUNJUNG ================= --}}
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

{{-- ================= DATA SURVEI ================= --}}
@if($mode === 'survei')

@if(isset($rekapSurvei) && $rekapSurvei->count())
<hr class="my-4">

<h5 class="mb-4 fw-bold">Rekapitulasi Hasil Survei Kepuasan</h5>

@php $totalResponden = $rekapSurvei->sum('total'); @endphp

<div class="row">
@foreach($rekapSurvei as $item)
@php
    $persen = $totalResponden > 0
        ? round(($item->total / $totalResponden) * 100, 1)
        : 0;
@endphp

<div class="col-md-3 mb-4">
    <div class="card shadow-sm h-100 text-center">
        <div class="card-body">
            <h6 class="fw-semibold">
                {{ is_array($item->jawaban) ? implode(', ', $item->jawaban) : $item->jawaban }}
            </h6>
            <div class="fs-4 fw-bold">{{ $item->total }}</div>
            <small class="text-muted">{{ $persen }}%</small>

            <div class="progress mt-2" style="height:6px">
                <div class="progress-bar bg-success" style="width:{{ $persen }}%"></div>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>

<div class="text-muted small mt-2">
    Total responden: {{ $totalResponden }}
</div>
@else
<p class="text-muted">Belum ada data survei.</p>
@endif
@endif

    </div>
</div>

<div class="d-flex justify-content-end mt-3">
    <a href="{{ route('admin.dashboard.exportPdf', request()->query()) }}" class="btn btn-danger">
        Download PDF
    </a>
</div>

{{-- ================= GRAFIK ================= --}}
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

new Chart(document.getElementById('grafikPengunjung'), {
    type: 'bar',
    data: { labels, datasets: [{ data, backgroundColor:'#4e73df', borderRadius:6 }] },
    options: { responsive:true, plugins:{legend:{display:false}} }
});

function showTabel(){
    sectionTabel.classList.remove('d-none');
    sectionGrafik.classList.add('d-none');
}
function showGrafik(){
    sectionGrafik.classList.remove('d-none');
    sectionTabel.classList.add('d-none');
}
</script>
@endif
@endpush
