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

<hr class="my-4">

<div class="d-flex align-items-center mb-3">
    <span class="text-muted">
        <strong>Rekapitulasi Survei Periode : {{ $periodeText }}</strong>
    </span>
</div>

<div class="row g-4">
@foreach($rekapSurvei as $index => $soal)
<div class="col-md-6">
    <div class="card shadow-sm h-100 border-0">
        <div class="card-body py-3">

            {{-- HEADER PERTANYAAN --}}
            <div class="d-flex align-items-start mb-3">
                <div class="me-2 text-success fs-5">
                    <i class="bi bi-chat-square-text"></i>
                </div>
                <h6 class="fw-bold mb-0">
                    {{ $soal['pertanyaan'] }}
                </h6>
            </div>

            {{-- OPSI JAWABAN --}}
            @foreach($soal['opsi'] as $opsi)
            @php
                $percent = $totalResponden
                    ? round(($opsi['total'] / $totalResponden) * 100)
                    : 0;

                $isTop = $percent >= 50;
            @endphp

            <div class="mb-3">
                <div class="d-flex justify-content-between small mb-1">
                    <span>
                        {{ $opsi['label'] }}
                        @if($isTop)
                            <span class="badge bg-success ms-1">
                                Terbanyak
                            </span>
                        @endif
                    </span>
                    <span class="fw-semibold">
                        {{ $percent }}%
                    </span>
                </div>

                <div class="progress" style="height:8px">
                    <div class="progress-bar
                        {{ $isTop ? 'bg-success' : 'bg-secondary' }}"
                        style="width:{{ $percent }}%">
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endforeach
</div>

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
@endsection
