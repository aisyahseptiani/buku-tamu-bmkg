<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengunjung BMKG</title>

    <style>
        /* ================== PAGE ================== */
        @page {
            margin: 1cm 3cm 3cm 3cm; /* atas kanan bawah kiri */
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }

        /* ================== KOP SURAT ================== */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }

        .kop-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            width: 85px;
        }

        .kop-text {
            text-align: center;
            padding-left: 10px;
        }

        .instansi {
            font-size: 14px;
            font-weight: bold;
            line-height: 1.4;
            text-transform: uppercase;
        }

        .alamat {
            font-size: 11px;
            margin-top: 3px;
        }

        .garis-kop {
            border-bottom: 3px double #000;
            margin-top: 8px;
            margin-bottom: 15px;
        }

        /* ================== JUDUL ================== */
        .judul {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .periode {
            margin-bottom: 10px;
            font-size: 12px;
        }

        /* ================== TABEL ================== */
        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        table.data th {
            text-align: center;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        /* ================== BLOK TTD ================== */
        .ttd-container {
            width: 100%;
            margin-top: 50px;
            text-align: right; /* kunci ke kanan halaman */
        }

        .ttd-box {
            display: inline-block;   /* kunci lebar kotak */
            width: 35%;              /* ukuran kotak ilustrasi */
            font-size: 12px;
        }

        /* baris pertama rata kanan */
        .ttd-tanggal {
            text-align: right;
            margin-bottom: 8px;
        }

        /* baris 2 & 3 rata tengah */
        .ttd-isi {
            text-align: left;
            line-height: 1.5;
        }

        /* nama */
        .ttd-nama {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
            text-align: center;
        }

    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <table class="kop-table">
        <tr>
            <td width="15%">
                <img src="{{ public_path('images/logo-bmkg.png') }}" class="logo">
            </td>
            <td width="85%" class="kop-text">
                <div class="instansi">
                    BADAN METEOROLOGI, KLIMATOLOGI, DAN GEOFISIKA<br>
                    STASIUN METEOROLOGI SULTAN SYARIF KASIM II PEKANBARU
                </div>
                <div class="alamat">
                    Bandar Udara Sultan Syarif Kasim II Pekanbaru (28284)<br>
                    Telp. (0761) 73701, Fax. 73701 – 674714<br>
                    Email : stamet.ssk2pku@bmkg.go.id
                </div>
            </td>
        </tr>
    </table>

    <div class="garis-kop"></div>

    <!-- JUDUL -->
    <div class="judul">
        LAPORAN PENGUNJUNG BMKG
    </div>

    <!-- PERIODE -->
    <div class="periode">
        @if(isset($tanggalMulai) && isset($tanggalAkhir))
            Laporan Pengunjung Periode
            {{ \Carbon\Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }}
            sampai
            {{ \Carbon\Carbon::parse($tanggalAkhir)->translatedFormat('d F Y') }}
        @else
            Hari, {{ now()->translatedFormat('d F Y') }}
        @endif
    </div>

    <!-- TABEL DATA -->
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Nama</th>
                <th width="20%">Instansi</th>
                <th width="20%">Tujuan</th>
                <th width="15%">No. Handphone</th>
                <th width="15%">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengunjungs as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->instansi }}</td>
                <td>{{ $p->tujuan }}</td>
                <td>{{ $p->no_hp }}</td>
                <td class="text-center">
                    {{ $p->created_at->format('d-m-Y H:i') }}
                </td>
            </tr>
            @endforeach

            <tr>
                <td colspan="5"><strong>Total</strong></td>
                <td class="text-center"><strong>{{ $pengunjungs->count() }}</strong></td>
            </tr>
        </tbody>
    </table>

    <!-- TTD -->
    <div class="ttd-container">
        <div class="ttd-box">

            <div class="ttd-tanggal">
                Pekanbaru, {{ now()->translatedFormat('d F Y') }}
            </div>

            <div class="ttd-isi">
                Kepala Stasiun Meteorologi Kelas I<br>
                Sultan Syarif Kasim II
            </div>

            <div class="ttd-nama">
                Irwansyah Nasution
            </div>

        </div>
    </div>


</body>
</html>
