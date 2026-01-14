<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Survei Kepuasan Pengunjung</title>

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

        .form-control,
        .form-select {
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
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

        /* ================= POPUP THANK YOU ================= */
        .thankyou-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255,255,255,.75);
            backdrop-filter: blur(6px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .thankyou-card {
            background: #ffffff;
            border-radius: 18px;
            padding: 30px 26px;
            max-width: 360px;
            width: 90%;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0,0,0,.12);
            animation: popupScale .35s ease;
        }

        @keyframes popupScale {
            from {
                opacity: 0;
                transform: scale(.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .thankyou-logo {
            width: 60px;
            margin-bottom: 10px;
        }

        .thankyou-icon {
            width: 70px;
            height: 70px;
            margin: 0 auto;
            border-radius: 50%;
            background: #e7f2ff;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thankyou-icon i {
            font-size: 32px;
            color: #0d6efd;
        }

        .thankyou-card p {
            font-size: 13px;
            line-height: 1.6;
        }
    </style>
</head>

<body>

{{-- ================= NAVBAR ================= --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm px-3 px-lg-4">
    <div class="container-fluid">

        <div class="navbar-brand d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo-bmkg.png') }}" alt="BMKG" height="38">
            <div class="lh-sm">
                <div class="fw-bold text-dark">Badan Meteorologi,</div>
                <div class="text-muted small">Klimatologi, dan Geofisika</div>
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

                    <div class="mb-4">
                        <h4 class="step-title mb-2">
                            SURVEI KEPUASAN PENGUNJUNG
                        </h4>
                        <p class="step-desc mb-0">
                            Mohon berikan penilaian Anda terhadap pelayanan
                            yang telah diberikan oleh BMKG.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('pengunjung.survei.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Tingkat Kepuasan Pelayanan</label>
                            <select name="kepuasan" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option>Sangat Puas</option>
                                <option>Puas</option>
                                <option>Cukup Puas</option>
                                <option>Tidak Puas</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Penilaian terhadap Petugas</label>
                            <select name="pelayanan" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <option>Sangat Baik</option>
                                <option>Baik</option>
                                <option>Cukup</option>
                                <option>Kurang</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Saran atau Masukan</label>
                            <textarea name="saran"
                                      rows="4"
                                      class="form-control"
                                      placeholder="Tuliskan saran Anda..."
                                      required></textarea>
                        </div>

                        <button type="submit" class="btn btn-next w-100">
                            KIRIM SURVEI
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

</main>

{{-- ================= POPUP ================= --}}
@if(session('success'))
<div class="thankyou-overlay" id="thankYouPopup">
    <div class="thankyou-card">

        <img src="{{ asset('images/logo-bmkg.png') }}" class="thankyou-logo" alt="BMKG">

        <div class="thankyou-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>

        <h5 class="fw-bold mt-3">Terima Kasih</h5>

        <p class="text-muted mt-2">
            Terima kasih telah berkunjung dan mengisi
            survei kepuasan pelayanan di
            <strong>BMKG</strong>.
        </p>

        <a href="/" class="btn btn-primary w-100 mt-3">
            Selesai
        </a>
    </div>
</div>
@endif

{{-- ================= SCRIPT ================= --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('thankYouPopup').style.display = 'flex';
    });
</script>
@endif

</body>
</html>
