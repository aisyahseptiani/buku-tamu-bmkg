@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

@php
    use Carbon\Carbon;
    $today = Carbon::now()->translatedFormat('l, d F Y');
@endphp

{{-- HEADER --}}
<div class="mb-4">
    <h3 class="fw-bold">Dashboard</h3>
    <div class="text-muted">{{ $today }}</div>
</div>

{{-- FILTER --}}
<form method="GET" class="row g-2 align-items-end mb-4">
    <div class="col-md-3">
        <label class="form-label">Filter</label>
        <select name="filter" class="form-select" onchange="this.form.submit()">
            <option value="hari" {{ request('filter')=='hari'?'selected':'' }}>Hari Ini</option>
            <option value="minggu" {{ request('filter')=='minggu'?'selected':'' }}>Mingguan</option>
            <option value="bulan" {{ request('filter')=='bulan'?'selected':'' }}>Bulanan</option>
            <option value="tahun" {{ request('filter')=='tahun'?'selected':'' }}>Tahunan</option>
        </select>
    </div>

    @if(request('filter') && request('filter') !== 'hari')
        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Tampilkan</button>
        </div>
    @endif
</form>

{{-- SWITCH + DOWNLOAD --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-primary active" id="btnTabel" onclick="showTabel()">
            Tabel
        </button>
        <button class="btn btn-outline-primary" id="btnGrafik" onclick="showGrafik()">
            Grafik
        </button>
    </div>

    <a href="{{ route('admin.dashboard.exportPdf', request()->query()) }}"
       class="btn btn-danger btn-sm">
        Download PDF
    </a>
    
</div>

{{-- TABEL --}}
<div class="card shadow-sm mb-4" id="sectionTabel">
    <div class="card-body">
        <h5 class="mb-3">Data Pengunjung</h5>

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
                            <td colspan="6" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- GRAFIK --}}
<div class="card shadow-sm d-none" id="sectionGrafik">
    <div class="card-body">
        <h5 class="mb-3">Grafik Pengunjung</h5>
        <canvas id="grafikPengunjung" height="120"></canvas>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
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

const labels = {!! json_encode(
    $grafik->pluck('tanggal')->map(fn($t) =>
        \Carbon\Carbon::parse($t)->translatedFormat('d M Y')
    )
) !!};

const data = {!! json_encode($grafik->pluck('total')) !!};

new Chart(document.getElementById('grafikPengunjung'), {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Jumlah Pengunjung',
            data: data,
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 10
                }
            }
        }
    }
});
</script>
@endpush
