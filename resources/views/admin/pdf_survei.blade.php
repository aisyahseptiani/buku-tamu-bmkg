<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Laporan Hasil Survei</title>

<style>

@page {
    margin: 2cm 2.5cm;
}

body {
    font-family: "Times New Roman", serif;
    font-size: 11px;
    color: #000;
}

/* ================= HEADER ================= */
.header-table {
    width: 100%;
    border-collapse: collapse;
}

.header-table td {
    border: none;
    vertical-align: middle;
}

.logo {
    width: 80px;
}

.header-text {
    text-align: center;
}

.instansi {
    font-size: 13px;
    font-weight: bold;
    text-transform: uppercase;
    line-height: 1.3;
}

.alamat {
    font-size: 10px;
}

.divider {
    border-bottom: 3px double #000;
    margin-top: 8px;
    margin-bottom: 15px;
}

/* ================= TITLE ================= */
.title {
    text-align: center;
    font-size: 13px;
    font-weight: bold;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.periode {
    margin-bottom: 10px;
}

/* ================= TABEL ================= */
table.data {
    width: 100%;
    border-collapse: collapse;
    margin: 0; /* hapus jarak antar tabel */
}

table.data th,
table.data td {
    border: 1px solid #000;
    padding: 4px 6px;
    font-size: 10.5px;
}

table.data th {
    text-align: center;
    font-weight: normal;
}

/* Shading profesional */
.shading {
    background-color: #e6e6e6;
}

.text-center {
    text-align: center;
}

/* ================= TTD ================= */
.signature {
    width: 100%;
    margin-top: 50px;
    text-align: right;
}

.signature-box {
    display: inline-block;
    width: 38%;
    text-align: center;
}

.signature-name {
    margin-top: 60px;
    font-weight: bold;
    text-decoration: underline;
}

</style>
</head>
<body>

<!-- HEADER -->
<table class="header-table">
<tr>
    <td width="15%">
        <img src="{{ public_path('images/logo-bmkg.png') }}" class="logo">
    </td>
    <td width="85%" class="header-text">
        <div class="instansi">
            BADAN METEOROLOGI, KLIMATOLOGI, DAN GEOFISIKA<br>
            STASIUN METEOROLOGI SULTAN SYARIF KASIM II PEKANBARU
        </div>
        <div class="alamat">
            Bandar Udara Sultan Syarif Kasim II Pekanbaru (28284)<br>
            Telp. (0761) 73701 – Email: stamet.ssk2pku@bmkg.go.id
        </div>
    </td>
</tr>
</table>

<div class="divider"></div>

<div class="title">LAPORAN HASIL SURVEI PELAYANAN</div>

<div class="periode">
    <strong>Periode :</strong> {{ $periodeText }}
</div>

<!-- ================= LOOP SOAL ================= -->
@foreach ($rekapSurvei as $item)

<table class="data">

    <!-- Judul Soal dengan Shading -->
    <tr>
        <td colspan="4" class="shading" style="font-weight: bold;">
            {{ $item['pertanyaan'] }}
        </td>
    </tr>

    <!-- Header Kolom -->
    <tr>
        <th width="8%">No</th>
        <th width="52%">Opsi Jawaban</th>
        <th width="20%">Jumlah</th>
        <th width="20%">Persentase</th>
    </tr>

    <!-- Data -->
    @foreach ($item['opsi'] as $opsi)
    <tr>
        <td class="text-center">{{ $loop->iteration }}</td>
        <td>{{ $opsi['label'] }}</td>
        <td class="text-center">{{ $opsi['total'] }}</td>
        <td class="text-center">{{ $opsi['persen'] }}%</td>
    </tr>
    @endforeach

</table>

@endforeach

<!-- ================= TOTAL RESPONDEN ================= -->
<table class="data">
    <tr>
        <td width="60%" class="shading">
            <strong>Total Responden</strong>
        </td>
        <td width="40%" class="text-center shading">
            <strong>{{ $totalResponden }} Orang</strong>
        </td>
    </tr>

</table>



<!-- ================= TTD ================= -->
<div class="signature">
    <div class="signature-box">
        Pekanbaru, {{ now()->translatedFormat('d F Y') }}<br>
        Plt. Kepala Stasiun Meteorologi Klas I<br>
        Sultan Syarif Kasim II Pekanbaru<br>

        <div class="signature-name">
            Warih Budi Lestari
        </div>
    </div>
</div>

</body>
</html>
