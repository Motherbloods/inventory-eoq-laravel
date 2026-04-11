@extends('layouts.auth')
@section('title', 'Masuk')

@section('content')
    <h6 class="text-center text-muted mb-3" style="font-size:0.85rem">Masuk ke akun Anda</h6>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" placeholder="email@tokorotiandika.com" autofocus
                required>
        </div>
        <div class="mb-3">
            <label class="form-label small fw-semibold">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="passwordInput"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                <span class="input-group-text" onclick="togglePass()">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </span>
            </div>
        </div>
        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small" for="remember">Ingat saya</label>
            </div>
        </div>
        <button type="submit" class="btn btn-login btn-primary w-100 text-white">
            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
        </button>
    </form>

    <p class="text-center text-muted mt-3 mb-0" style="font-size:0.75rem">
        Toko Roti Andika &mdash; 2026
    </p>

    <script>
        function togglePass() {
            const input = document.getElementById('passwordInput');
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
@endsection
