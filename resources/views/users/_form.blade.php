<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap pengguna" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror" placeholder="email@contoh.com" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Role <span class="text-danger">*</span></label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach (['pemilik' => 'Pemilik', 'admin' => 'Admin / Petugas Gudang', 'produksi' => 'User Produksi'] as $val => $label)
                <option value="{{ $val }}" {{ old('role', $user->role ?? '') == $val ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Password {{ isset($isEdit) ? '(kosongkan jika tidak diubah)' : '' }} <span
                class="text-danger">*</span></label>
        <div class="input-group">
            <input type="password" name="password" id="passInput"
                class="form-control @error('password') is-invalid @enderror" placeholder="Min. 8 karakter"
                {{ isset($isEdit) ? '' : 'required' }}>
            <span class="input-group-text" style="cursor:pointer" onclick="togglePass()">
                <i class="bi bi-eye" id="eyeIcon"></i>
            </span>
        </div>
        @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Konfirmasi Password {{ isset($isEdit) ? '' : '' }}</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $user->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ old('is_active', $user->is_active ?? 1) == 0 ? 'selected' : '' }}>Non-Aktif
            </option>
        </select>
    </div>
</div>
<script>
    function togglePass() {
        const i = document.getElementById('passInput');
        const e = document.getElementById('eyeIcon');
        i.type = i.type === 'password' ? 'text' : 'password';
        e.classList.toggle('bi-eye');
        e.classList.toggle('bi-eye-slash');
    }
</script>
