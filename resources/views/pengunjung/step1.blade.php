<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Diri Pengunjung</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6fb;
            font-family: 'Segoe UI', sans-serif;
            font-size: 0.95rem;
        }

        /* ================= NAVBAR ================= */
        .navbar-brand {
            font-size: 0.95rem;
        }

        /* ================= CARD ================= */
        .card-step {
            border: none;
            border-radius: 18px;
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
        }

        .step-title {
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: .4px;
        }

        .step-desc {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ================= FORM ================= */
        .form-label {
            font-weight: 700;
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #4f8cff;
        }

        /* ================= BUTTON ================= */
        .btn-next {
            background: linear-gradient(90deg, #4f8cff, #3b73e6);
            border: none;
            padding: 11px;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 10px;
        }

        .btn-next:hover {
            background: linear-gradient(90deg, #3b73e6, #2f5fd0);
        }
    </style>
</head>

<body>

{{-- ================= NAVBAR ================= --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm px-3 px-lg-4">
    <div class="container-fluid">

        {{-- LOGO + NAMA (MOBILE & DESKTOP SAMA) --}}
        <div class="navbar-brand d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo-bmkg.png') }}" alt="BMKG" height="38">

            <div class="lh-sm">
                <div class="fw-bold text-dark">
                    Badan Meteorologi,
                </div>
                <div class="text-muted small">
                    Klimatologi, dan Geofisika
                </div>
            </div>
        </div>

    </div>
</nav>

{{-- ================= CONTENT ================= --}}
<main class="container my-4">

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="card card-step">
                <div class="card-body p-4 p-md-5">

                    {{-- JUDUL --}}
                    <div class="mb-4">
                        <h4 class="step-title mb-2">
                            BUKU TAMU DIGITAL
                        </h4>
                        <p class="step-desc mb-0">
                            Silakan lengkapi data diri Anda sebelum melanjutkan ke
                            survei kepuasan layanan BMKG.
                        </p>
                    </div>

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('pengunjung.step1.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text"
                                   name="nama"
                                   class="form-control"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Instansi</label>
                            <input type="text"
                                   name="instansi"
                                   class="form-control"
                                   placeholder="Asal instansi">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tujuan Kunjungan</label>
                            <input type="text"
                                   name="tujuan"
                                   class="form-control"
                                   placeholder="Contoh: Konsultasi data cuaca"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">No. Handphone</label>
                            <input type="text"
                                   name="no_hp"
                                   class="form-control"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <button type="submit" class="btn btn-next w-100">
                            Selanjutnya
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</main>

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
