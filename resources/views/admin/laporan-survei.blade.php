@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">📊 Laporan Survei Pengunjung</h4>

        <form class="d-flex gap-2" method="GET">
            <select name="bulan" class="form-select">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ ($bulan ?? now()->month) == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                    </option>
                @endfor
            </select>

            <select name="tahun" class="form-select">
                @for($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ ($tahun ?? now()->year) == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                @endfor
            </select>

            <button class="btn btn-primary">Tampilkan</button>
        </form>
    </div>

    {{-- ======================
        REKAP PERTANYAAN
    ====================== --}}
    @forelse($rekap as $item)
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-bold">
                {{ $item['label'] }}
            </div>

            <div class="card-body p-0">
                <table class="table mb-0 table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Opsi Jawaban</th>
                            <th width="120">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item['data'] as $opsi => $total)
                            <tr>
                                <td>{{ $opsi }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $total }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="alert alert-warning">
            Belum ada data survei.
        </div>
    @endforelse


    {{-- ======================
        SARAN PENGUNJUNG
    ====================== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">
            📝 Saran Pengunjung
        </div>
        <div class="card-body">
            <ul class="mb-0">
                @forelse($saran as $item)
                    <li>{{ $item }}</li>
                @empty
                    <li class="text-muted">Belum ada saran</li>
                @endforelse
            </ul>
        </div>
    </div>


    {{-- ======================
        MASUKAN PENGUNJUNG
    ====================== --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">
            💡 Masukan Pengunjung
        </div>
        <div class="card-body">
            <ul class="mb-0">
                @forelse($masukan as $item)
                    <li>{{ $item }}</li>
                @empty
                    <li class="text-muted">Belum ada masukan</li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
@endsection
