<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Navbar custom */
        .navbar-brand span {
            font-size: 1.1rem;
            letter-spacing: .5px;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
        }

        .navbar-nav .nav-link.active {
            color: #0d6efd !important;
        }

        .btn-logout {
            border-radius: 20px;
            padding: 6px 16px;
        }
    </style>

    @stack('style')
</head>

<body class="bg-light">

{{-- ================= NAVBAR ================= --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm px-3 px-lg-4">
    <div class="container-fluid">

        {{-- LOGO + NAMA --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/logo-bmkg.png') }}" alt="BMKG" height="40">

            {{-- MOBILE --}}
            <span class="fw-bold text-dark d-lg-none">
                BMKG
            </span>

            {{-- DESKTOP --}}
            <div class="d-none d-lg-block lh-sm">
                <div class="fw-bold text-dark">
                    Badan Meteorologi,
                </div>
                <div class="text-muted small">
                    Klimatologi, dan Geofisika
                </div>
            </div>
        </a>

        {{-- TOGGLER --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarAdmin">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU --}}
        <div class="collapse navbar-collapse justify-content-end" id="navbarAdmin">
            <ul class="navbar-nav align-items-lg-center gap-lg-3 mt-3 mt-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? 'active fw-semibold' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/qr*') ? 'active fw-semibold' : '' }}"
                       href="{{ route('admin.qr.index') }}">
                        <i class="bi bi-qr-code me-1"></i> QR Pengunjung
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="btn btn-outline-danger btn-sm btn-logout">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </div>
</nav>

{{-- ================= CONTENT ================= --}}
<main class="container my-4">
    @yield('content')
</main>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@stack('script')

</body>
</html>
