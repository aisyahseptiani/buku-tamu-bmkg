@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
@php
    use Carbon\Carbon;
    $today = Carbon::now()->translatedFormat('l, d F Y');
@endphp

{{-- ================= HEADER ================= --}}
<div class="mb-4">
    <h3 class="fw-bold">Dashboard</h3>
    <div class="text-muted">{{ $today }}</div>
</div>

{{-- ================= FILTER ================= --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET">

            {{-- ROW UTAMA --}}
            <div class="row g-3 align-items-end">

                {{-- JENIS FILTER --}}
                <div class="col-md-3">
                    <label class="form-label small fw-semibold mb-1">Jenis Filter</label>
                    <select name="filter" class="form-select form-select-sm">
                        <option value="hari" {{ request('filter')=='hari'?'selected':'' }}>Hari Ini</option>
                        <option value="minggu" {{ request('filter')=='minggu'?'selected':'' }}>Mingguan</option>
                        <option value="bulan" {{ request('filter')=='bulan'?'selected':'' }}>Bulanan</option>
                        <option value="tahun" {{ request('filter')=='tahun'?'selected':'' }}>Tahunan</option>
                    </select>
                </div>

                {{-- RENTANG TANGGAL --}}
                @if(request('filter') !== 'hari')
                <div class="col-md-5">
                    <label class="form-label small fw-semibold mb-1 d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor"
                             class="me-1">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3M3 11h18M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                        Rentang Tanggal
                    </label>

                    <div class="input-group input-group-sm">
                        <input type="date" name="from" class="form-control"
                               value="{{ request('from') }}">
                        <span class="input-group-text bg-white">—</span>
                        <input type="date" name="to" class="form-control"
                               value="{{ request('to') }}">
                    </div>
                </div>
                @endif

                {{-- BUTTON --}}
                <div class="col-md-2">
                    <button class="btn btn-primary btn-sm w-100 fw-semibold">
                        Tampilkan
                    </button>
                </div>

            </div>

        </form>
    </div>
</div>


{{-- ================= SWITCH & DOWNLOAD ================= --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="btn-group btn-group-sm">
        <button type="button"
                class="btn btn-outline-primary active"
                id="btnTabel"
                onclick="showTabel()">
            Tabel
        </button>
        <button type="button"
                class="btn btn-outline-primary"
                id="btnGrafik"
                onclick="showGrafik()">
            Grafik
        </button>
    </div>

    
</div>

{{-- ================= TABEL ================= --}}
<div class="card shadow-sm mb-4" id="sectionTabel">
    <div class="card-body">
        <h5 class="mb-3">Data Pengunjung</h5>

        {{-- ===== TAHUNAN ===== --}}
        @if($filter === 'tahun')
            <table class="table table-bordered ">
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

        {{-- ===== HARI / RANGE ===== --}}
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
                                <td colspan="6" class="text-center text-muted">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="d-flex justify-content-end mt-3">
    <a href="{{ route('admin.dashboard.exportPdf', request()->query()) }}"
       class="btn btn-danger">
         Download PDF
    </a>
</div>

{{-- ================= GRAFIK ================= --}}
<div class="card shadow-sm d-none" id="sectionGrafik">
    <div class="card-body">
        <h5 class="mb-3">Grafik Pengunjung</h5>
        <div style="height:360px">
            <canvas id="grafikPengunjung"></canvas>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0,     // ⬅️ TANPA DESIMAL
                    stepSize: 1       // ⬅️ BILANGAN BULAT
                }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// ================= SWITCH =================
function showTabel() {
    document.getElementById('sectionTabel').classList.remove('d-none');
    document.getElementById('sectionGrafik').classList.add('d-none');

    btnTabel.classList.add('active');
    btnGrafik.classList.remove('active');
}

function showGrafik() {
    document.getElementById('sectionGrafik').classList.remove('d-none');
    document.getElementById('sectionTabel').classList.add('d-none');

    btnGrafik.classList.add('active');
    btnTabel.classList.remove('active');
}
</script>
@endpush
