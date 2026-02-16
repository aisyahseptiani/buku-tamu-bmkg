<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Buku Tamu Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .qr-wrapper {
            position: relative;
            background: #ffffff;
            border-radius: 32px;
            padding: 70px 80px;
            box-shadow: 
                0 40px 80px rgba(0,0,0,0.06),
                0 10px 20px rgba(0,0,0,0.04);
            text-align: center;
            max-width: 650px;
            width: 100%;
            animation: fadeIn 0.8s ease;
        }

        /* Accent line super subtle */
        .qr-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 6px;
            border-radius: 0 0 20px 20px;
            background: linear-gradient(to right, #0d6efd, #198754);
        }

        .title {
            font-size: 1.9rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 55px;
        }

        .qr-box {
            position: relative;
            background: #ffffff;
            padding: 45px;
            border-radius: 28px;
            box-shadow: 
                0 25px 50px rgba(0,0,0,0.05);
            display: inline-block;
        }

        .qr-box::after {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 32px;
            border: 2px solid rgba(25,135,84,0.35);
        }

        .qr-box img {
            width: 280px;
            height: 280px;
        }

        .footer-text {
            margin-top: 50px;
            font-size: 0.85rem;
            color: #94a3b8;
            letter-spacing: 0.5px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .qr-wrapper {
                padding: 50px 30px;
            }

            .qr-box img {
                width: 220px;
                height: 220px;
            }
        }
    </style>
</head>
<body>

<div class="qr-wrapper">

    <div class="title">Scan QR Buku Tamu Digital</div>

    <div class="subtitle">
        Silakan scan QR Code berikut untuk mengisi buku tamu digital dan survei kepuasan layanan.
    </div>

    <div class="qr-box">
        <img src="{{ asset('storage/'.$qr->file_path) }}" alt="QR Code">
    </div>

    <div class="footer-text">
        © 2026 Stamet SSK II Pekanbaru 
    </div>

</div>

</body>
</html>
