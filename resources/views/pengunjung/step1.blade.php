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
            background: linear-gradient(180deg, #f6f8fc, #eef2f8);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            font-size: 0.95rem;
            color: #1f2937;
        }

        /* ================= NAVBAR ================= */
        .navbar {
            backdrop-filter: blur(6px);
        }

        .navbar-brand {
            font-size: 0.95rem;
        }

        /* ================= CARD ================= */
        .card-step {
            border: none;
            border-radius: 20px;
            background: #ffffff;
            box-shadow:
                0 10px 25px rgba(0,0,0,.06),
                0 1px 3px rgba(0,0,0,.05);
        }

        .step-title {
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: .6px;
        }

        .step-desc {
            color: #6b7280;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        /* ================= FORM ================= */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 0.9rem;
            border: 1px solid #e5e7eb;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-control:focus {
            border-color: #4f8cff;
            box-shadow: 0 0 0 3px rgba(79,140,255,.15);
        }

        /* ================= BUTTON ================= */
        .btn-next {
            background: linear-gradient(135deg, #4f8cff, #356fe0);
            border: none;
            padding: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 14px;
            letter-spacing: .3px;
        }

        .btn-next:hover {
            background: linear-gradient(135deg, #356fe0, #2f5fd0);
        }

        img {
            filter: drop-shadow(0 2px 4px rgba(0,0,0,.15));
        }


    </style>
</head>

<body>
{{-- ================= CONTENT ================= --}}
<main class="container my-4">

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <div class="card card-step">
                <div class="card-body p-4 p-md-5">
                    {{-- HEADER CARD --}}
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <img src="{{ asset('images/logo-bmkg.png') }}"
                            alt="BMKG"
                            height="42">

                        <div class="lh-sm">
                            <div class="fw-bold text-dark">
                                Badan Meteorologi, Klimatologi, dan Geofisika
                            </div>
                            <div class="text-muted small">
                                STAMET SSK II PEKANBARU
                            </div>
                        </div>
                    </div>


                    {{-- JUDUL --}}
                    <div class="mb-4">
                        <h4 class="step-title mb-2">
                            Buku Tamu Digital
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
