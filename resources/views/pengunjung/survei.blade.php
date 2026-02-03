<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Survei Kepuasan Pengunjung</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(180deg, #f6f8fc, #eef2f8);
            font-family: 'Segoe UI', system-ui, sans-serif;
            font-size: 0.95rem;
            color: #111827;
        }

        /* CARD */
        .card-survei {
            border: none;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 20px 40px rgba(0,0,0,.08);
        }

        /* HEADER CARD */
        .card-header-custom {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }

        /* TITLE */
        .title {
            font-weight: 800;
            letter-spacing: .5px;
            font-size: 1.25rem;
        }

        .desc {
            font-size: .9rem;
            color: #6b7280;
            line-height: 1.6;
        }

        /* LABEL */
        .form-label {
            font-weight: 600;
            font-size: .85rem;
            color: #374151;
            margin-bottom: 6px;
        }

        /* DROPDOWN PREMIUM */
        .select-wrapper {
            position: relative;
        }

        .select-wrapper svg {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #6b7280;
            pointer-events: none;
        }

        .form-select {
            /* appearance: none;
            background-color: #f9fafb;
            border-radius: 14px;
            padding: 14px 46px 14px 16px;
            font-size: 0.9rem;
            font-weight: 500;
            border: 2px solid #e5e7eb;
            transition: all .2s ease; */

            appearance: none;          /* Standard */
            -webkit-appearance: none;  /* Chrome / Safari */
            -moz-appearance: none;     /* Firefox */
            background-image: none !important;
        }

        .form-select:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,.15);
        }

        /* BUTTON */
        .btn-submit {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none;
            padding: 14px;
            font-weight: 700;
            font-size: .9rem;
            border-radius: 14px;
            letter-spacing: .4px;
        }

        .btn-submit:hover {
            opacity: .95;
        }
    </style>
</head>

<body>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <div class="card card-survei">
                <div class="card-body p-4 p-md-5">

                    {{-- HEADER --}}
                    <div class="card-header-custom d-flex align-items-center gap-3">
                        <img src="{{ asset('images/logo-bmkg.png') }}" height="46">

                        <div class="lh-sm">
                            <div class="fw-bold">
                                Badan Meteorologi, Klimatologi, dan Geofisika
                            </div>
                            <div class="text-muted small">
                                STAMET SSK II PEKANBARU
                            </div>
                        </div>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-4">
                        <div class="title mb-2">
                            SURVEI KEPUASAN PENGUNJUNG
                        </div>
                        <p class="desc mb-0">
                            Mohon berikan penilaian Anda terhadap pelayanan
                            yang telah diberikan oleh Stasiun Meteorologi.
                        </p>
                    </div>

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('pengunjung.survei.store') }}">
                        @csrf

                        {{-- LOOP PERTANYAAN --}}
                        @php
                        $questions = [
                            'persyaratan' => [
                                'label' => 'persyaratan pelayanan',
                                'opsi'  => ['Sangat Praktis', 'Cukup Praktis', 'Kurang Praktis', 'Tidak Praktis']
                            ],
                            'kemudahan' => [
                                'label' => 'kemudahan pelayanan',
                                'opsi'  => ['Sangat Mudah', 'Cukup Mudah', 'Kurang Mudah', 'Tidak Mudah']
                            ],
                            'kecepatan' => [
                                'label' => 'kecepatan petugas',
                                'opsi'  => ['Sangat Cepat', 'Cukup Cepat', 'Kurang Cepat', 'Tidak Cepat']
                            ],
                            'akses_informasi' => [
                                'label' => 'kemudahan akses informasi Meteorologi Penerbangan',
                                'opsi'  => ['Sangat Mudah', 'Cukup Mudah', 'Kurang Mudah', 'Tidak Mudah']
                            ],
                            'website' => [
                                'label' => 'pelayanan informasi website/media sosial',
                                'opsi'  => ['Sangat Praktis', 'Cukup Praktis', 'Kurang Praktis', 'Tidak Praktis']
                            ],
                            'kesesuaian_produk' => [
                                'label' => 'kesesuaian produk pelayanan',
                                'opsi'  => ['Sangat Sesuai', 'Cukup Sesuai', 'Kurang Sesuai', 'Tidak Sesuai']
                            ],
                            'kompetensi' => [
                                'label' => 'kemampuan petugas',
                                'opsi'  => ['Sangat Mampu', 'Cukup Mampu', 'Kurang Mampu', 'Tidak Mampu']
                            ],
                            'kesopanan' => [
                                'label' => 'kesopanan dan perilaku petugas',
                                'opsi'  => ['Sangat Sopan/Ramah', 'Cukup Sopan/Ramah', 'Kurang Sopan/Ramah', 'Tidak Sopan/Ramah']
                            ],
                            'sarana' => [
                                'label' => 'kualitas sarana dan prasarana',
                                'opsi'  => ['Sangat Baik', 'Cukup', 'Kurang Baik', 'Tidak Baik']
                            ],
                            'pengaduan' => [
                                'label' => 'penanganan pengaduan pengguna layanan',
                                'opsi'  => ['Dikelola Sangat Baik', 'Dikelola Cukup Baik', 'Kurang Maksimal', 'Tidak Berfungsi']
                            ],
                        ];
                        @endphp

                        @foreach($questions as $key => $q)
                        <div class="mb-4">
                            <label class="form-label">
                                Bagaimana pendapat saudara tentang {{ $q['label'] }}?
                            </label>

                            <div class="select-wrapper">
                                <select name="jawaban[{{ $key }}]" class="form-select" required>
                                    <option value="">Pilih jawaban</option>
                                    @foreach($q['opsi'] as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @endforeach

                        {{-- SARAN --}}
                        <div class="mb-4">
                            <label class="form-label">
                                Saran
                            </label>

                            <textarea
                                name="saran"
                                class="form-control"
                                rows="3"
                                placeholder="Tuliskan saran Anda..."
                                style="
                                    border-radius: 14px;
                                    font-size: 0.9rem;
                                    padding: 14px 16px;
                                    border: 2px solid #e5e7eb;
                                "
                            ></textarea>
                        </div>

                        {{-- MASUKAN --}}
                        <div class="mb-4">
                            <label class="form-label">
                                Masukan
                            </label>

                            <textarea
                                name="masukan"
                                class="form-control"
                                rows="3"
                                placeholder="Tuliskan masukan Anda..."
                                style="
                                    border-radius: 14px;
                                    font-size: 0.9rem;
                                    padding: 14px 16px;
                                    border: 2px solid #e5e7eb;
                                "
                            ></textarea>
                        </div>

                        <button class="btn btn-submit w-100 mt-3">
                            Kirim
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
