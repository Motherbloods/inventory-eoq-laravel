<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Inventory Toko Roti Andika</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <div class="login-wrapper">

        <div class="panel-left">
            <div class="deco-dot"></div>

            <div class="brand">
                <img src="{{ asset('logo_login.png') }}" alt="Logo"
                    style="width: 32px; height: 32px; object-fit: contain;">

                <div>
                    <div class="brand-name">Toko Roti Andika</div>
                    <div class="brand-sub">Sistem Informasi Manajemen Inventory</div>
                </div>
            </div>

            <div class="panel-body">
                <h2>Kelola inventory<br>bahan baku roti</h2>
                <p>Sistem manajemen persediaan berbasis web dengan metode EOQ untuk Toko Roti Andika.</p>
                <ul class="feat-list">
                    <li>
                        <span class="feat-check"><i class="bi bi-check"></i></span>
                        Manajemen stok bahan baku
                    </li>
                    <li>
                        <span class="feat-check"><i class="bi bi-check"></i></span>
                        Perhitungan EOQ otomatis
                    </li>
                    <li>
                        <span class="feat-check"><i class="bi bi-check"></i></span>
                        Notifikasi stok minimum
                    </li>
                    <li>
                        <span class="feat-check"><i class="bi bi-check"></i></span>
                        Laporan & reorder point real-time
                    </li>
                </ul>
            </div>

            <div class="panel-foot">&copy; {{ date('Y') }} Toko Roti Andika</div>
        </div>

        <div class="panel-right">
            <div class="form-head">
                <h3>Selamat datang</h3>
                <p>Masukkan kredensial Anda untuk masuk ke sistem inventory.</p>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <i class="bi bi-exclamation-circle" style="font-size:13px;flex-shrink:0"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="field">
                    <label class="field-label">Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" name="email" class="field-input @error('email') is-invalid @enderror"
                            placeholder="email@tokorotiandika.com" value="{{ old('email') }}" autofocus required>
                    </div>
                </div>

                <div class="field">
                    <label class="field-label">Password</label>
                    <div class="input-wrap">
                        <i class="bi bi-lock field-icon"></i>
                        <input type="password" name="password" id="pwInput"
                            class="field-input @error('password') is-invalid @enderror" placeholder="••••••••" required>
                        <button type="button" class="toggle-pw" onclick="togglePw()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk ke sistem
                </button>
            </form>

            <p class="form-foot">Toko Roti Andika &mdash; {{ date('Y') }}</p>
        </div>

    </div>

    <script>
        function togglePw() {
            const input = document.getElementById('pwInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>

</body>

</html>
