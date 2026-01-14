<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin | Buku Tamu BMKG</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
        }
        .login-wrapper {
            min-height: 100vh;
        }
        .login-card {
            border-radius: 14px;
            border: none;
        }
        .logo {
            width: 90px;
        }
        .title {
            font-weight: 600;
            margin-bottom: 0;
        }
        .subtitle {
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="container login-wrapper d-flex align-items-center justify-content-center">
    <div class="col-md-5 col-lg-4">
        <div class="card login-card shadow-sm">
            <div class="card-body p-4 text-center">

                <!-- HEADER LOGO + TITLE -->
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <img src="{{ asset('images/logo-bmkg.png') }}"
                        style="width:55px"
                        class="me-2"
                        alt="BMKG">

                    <div class="text-start">
                        <h6 class="fw-bold mb-0">BUKU TAMU DIGITAL</h6>
                        <small class="text-muted">
                            Badan Meteorologi, Klimatologi, dan Geofisika
                        </small>
                    </div>
                </div>

                <hr>

                <!-- ALERT -->
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- FORM LOGIN -->
                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf

                    <div class="mb-3 text-start">
                        <label class="form-label">Username</label>
                        <input type="text"
                               name="username"
                               class="form-control"
                               placeholder="Masukkan username"
                               required autofocus>
                    </div>

                    <div class="mb-3 text-start">
                        <label class="form-label">Password</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Masukkan password"
                               required>
                    </div>

                    <button class="btn btn-primary w-100 mt-2">
                        Login
                    </button>
                </form>

                <p class="text-muted mt-3 mb-0" style="font-size: 12px;">
                    © {{ date('Y') }} BMKG
                </p>

            </div>
        </div>
    </div>
</div>

</body>
</html>
