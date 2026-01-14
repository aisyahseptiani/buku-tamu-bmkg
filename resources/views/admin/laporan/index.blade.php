<h3>Laporan Pengunjung BMKG</h3>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="filter" class="form-select">
            <option value="">-- Pilih Filter --</option>
            <option value="minggu" {{ $filter=='minggu'?'selected':'' }}>Mingguan</option>
            <option value="bulan" {{ $filter=='bulan'?'selected':'' }}>Bulanan</option>
            <option value="tahun" {{ $filter=='tahun'?'selected':'' }}>Tahunan</option>
        </select>
    </div>

    <div class="col-md-2">
        <button class="btn btn-primary">Tampilkan</button>
    </div>

    @if($filter)
    <div class="col-md-3">
        <a href="/admin/laporan/pdf?filter={{ $filter }}" class="btn btn-danger">
            Download PDF
        </a>
    </div>
    @endif
</form>

<!--tabel data-->
<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Instansi</th>
    <th>Tujuan</th>
    <th>No HP</th>
    <th>Tanggal</th>
</tr>
</thead>
<tbody>
@forelse($hasil as $i => $h)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $h->nama }}</td>
    <td>{{ $h->instansi }}</td>
    <td>{{ $h->tujuan }}</td>
    <td>{{ $h->no_hp }}</td>
    <td>{{ $h->created_at }}</td>
</tr>
@empty
<tr>
    <td colspan="6" class="text-center">Data tidak tersedia</td>
</tr>
@endforelse
</tbody>
</table>

