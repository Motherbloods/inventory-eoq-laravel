<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nama Pemasok <span class="text-danger">*</span></label>
        <input type="text" name="nama_pemasok" value="{{ old('nama_pemasok', $pemasok->nama_pemasok ?? '') }}"
            class="form-control @error('nama_pemasok') is-invalid @enderror"
            placeholder="Nama perusahaan atau toko pemasok" required>
        @error('nama_pemasok')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Kontak Person</label>
        <input type="text" name="kontak_person" value="{{ old('kontak_person', $pemasok->kontak_person ?? '') }}"
            class="form-control" placeholder="Nama narahubung">
    </div>
    <div class="col-md-4">
        <label class="form-label">Telepon</label>
        <input type="text" name="telepon" value="{{ old('telepon', $pemasok->telepon ?? '') }}" class="form-control"
            placeholder="08xx-xxxx-xxxx">
    </div>
    <div class="col-md-4">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $pemasok->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror" placeholder="email@pemasok.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="is_active" class="form-select">
            <option value="1" {{ old('is_active', $pemasok->is_active ?? 1) == 1 ? 'selected' : '' }}>Aktif
            </option>
            <option value="0" {{ old('is_active', $pemasok->is_active ?? 1) == 0 ? 'selected' : '' }}>Non-Aktif
            </option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" rows="2" class="form-control" placeholder="Alamat lengkap pemasok">{{ old('alamat', $pemasok->alamat ?? '') }}</textarea>
    </div>
</div>
