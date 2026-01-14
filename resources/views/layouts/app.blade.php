<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Buku Tamu BMKG')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #e8f5f2, #eef4ff);
            min-height: 100vh;
        }

        .card-bmkg {
            border-radius: 18px;
            border: none;
        }

        .btn-bmkg {
            background: linear-gradient(90deg, #0d6efd, #20c997);
            border: none;
        }

        .btn-bmkg:hover {
            opacity: .9;
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-light bg-white shadow-sm px-3">
    <div class="container-fluid d-flex align-items-center gap-3">
        <img src="{{ asset('images/logo-bmkg.png') }}" height="40" alt="BMKG">

        <div class="lh-sm">
            <div class="fw-bold text-dark">Badan Meteorologi,</div>
            <div class="text-muted small">Klimatologi, dan Geofisika</div>
        </div>
    </div>
</nav>

{{-- CONTENT --}}
<main class="container py-5">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
