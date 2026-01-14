<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Tamu BMKG</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 420px">
        <div class="card-body text-center">

            <h4 class="fw-bold mb-3">Buku Tamu BMKG</h4>

            <p class="text-muted">
                Silakan lanjutkan untuk mengisi data kunjungan
            </p>

            <a href="{{ route('pengunjung.step1') }}"
               class="btn btn-primary w-100">
                Isi Buku Tamu
            </a>

        </div>
    </div>
</div>

</body>
</html>
