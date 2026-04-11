<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — Inventory Toko Roti Andika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e2a3a 0%, #2d3f55 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .auth-logo {
            width: 52px;
            height: 52px;
            background: #e65c1e;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            margin: 0 auto 1rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.6rem 0.9rem;
        }

        .form-control:focus {
            border-color: #e65c1e;
            box-shadow: 0 0 0 0.2rem rgba(230, 92, 30, 0.15);
        }

        .btn-login {
            background: #e65c1e;
            border: none;
            border-radius: 8px;
            padding: 0.65rem;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #c94e15;
        }

        .input-group-text {
            border-radius: 0 8px 8px 0;
            background: #f8f9fa;
            border-color: #dee2e6;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="text-center mb-4">
            <div class="auth-logo"><i class="bi bi-box-seam"></i></div>
            <h5 class="fw-bold mb-0">Toko Roti Andika</h5>
            <p class="text-muted small mb-0">Sistem Informasi Manajemen Inventory</p>
        </div>
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
