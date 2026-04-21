<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Kesalahan Server</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            background: #ffeaea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
    </style>
</head>

<body>
    <div class="text-center p-4">
        <div class="error-icon"><i class="bi bi-exclamation-triangle text-danger" style="font-size:2rem"></i></div>
        <h1 class="fw-bold mb-1" style="font-size:3rem;color:#e65c1e">500</h1>
        <h5 class="fw-semibold mb-2">Kesalahan Server</h5>
        <p class="text-muted mb-4">Terjadi kesalahan pada server. Silakan coba beberapa saat lagi.</p>
        <a href="{{ url('/dashboard') }}" class="btn btn-primary text-white">
            <i class="bi bi-house me-1"></i>Kembali ke Dashboard
        </a>
    </div>
</body>

</html>
