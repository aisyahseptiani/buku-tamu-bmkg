<h2 style="text-align:center">
    Laporan Pengunjung BMKG ({{ strtoupper($filter) }})
</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Instansi</th>
    <th>Tujuan</th>
    <th>Tanggal</th>
</tr>
@foreach($hasil as $i => $h)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $h->nama }}</td>
    <td>{{ $h->instansi }}</td>
    <td>{{ $h->tujuan }}</td>
    <td>{{ $h->created_at }}</td>
</tr>
@endforeach
</table>
