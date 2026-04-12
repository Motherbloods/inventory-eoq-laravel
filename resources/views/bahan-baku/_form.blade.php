<div class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Kode Bahan <span class="text-danger">*</span></label>
        <input type="text" name="kode_bahan" value="{{ old('kode_bahan', $bahanBaku->kode_bahan ?? '') }}"
            class="form-control @error('kode_bahan') is-invalid @enderror" placeholder="Cth: BB-001" maxlength="20"
            required>
        @error('kode_bahan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Bahan <span class="text-danger">*</span></label>
        <input type="text" name="nama_bahan" value="{{ old('nama_bahan', $bahanBaku->nama_bahan ?? '') }}"
            class="form-control @error('nama_bahan') is-invalid @enderror" placeholder="Nama lengkap bahan baku"
            required>
        @error('nama_bahan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Kategori <span class="text-danger">*</span></label>
        <input type="text" name="kategori" value="{{ old('kategori', $bahanBaku->kategori ?? '') }}"
            class="form-control @error('kategori') is-invalid @enderror" list="kategoriList"
            placeholder="Pilih atau ketik kategori" required>
        <datalist id="kategoriList">
            @foreach ($kategoris as $k)
                <option value="{{ $k }}">
            @endforeach
            <option value="Tepung">
            <option value="Gula & Pemanis">
            <option value="Lemak & Minyak">
            <option value="Telur & Susu">
            <option value="Ragi & Pengembang">
            <option value="Bahan Tambahan">
            <option value="Isian & Topping">
            <option value="Kemasan">
        </datalist>
        @error('kategori')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Satuan <span class="text-danger">*</span></label>
        <select name="satuan" class="form-select @error('satuan') is-invalid @enderror" required>
            <option value="">-- Pilih Satuan --</option>
            @foreach (['kg', 'gram', 'liter', 'ml', 'pcs', 'lusin', 'pak', 'karung', 'kaleng'] as $s)
                <option value="{{ $s }}"
                    {{ old('satuan', $bahanBaku->satuan ?? '') == $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
        @error('satuan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Harga Satuan (Rp) <span class="text-danger">*</span></label>
        <input type="number" name="harga_satuan" value="{{ old('harga_satuan', $bahanBaku->harga_satuan ?? '') }}"
            class="form-control @error('harga_satuan') is-invalid @enderror" min="0" step="0.01"
            placeholder="0" required>
        @error('harga_satuan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
        <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $bahanBaku->stok_minimum ?? '') }}"
            class="form-control @error('stok_minimum') is-invalid @enderror" min="0" step="0.01"
            placeholder="0" required>
        <div class="form-text">Batas minimum sebelum notifikasi muncul.</div>
        @error('stok_minimum')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
        <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', $bahanBaku->stok_saat_ini ?? '0') }}"
            class="form-control @error('stok_saat_ini') is-invalid @enderror" min="0" step="0.01" required>
        @error('stok_saat_ini')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" rows="2" class="form-control @error('deskripsi') is-invalid @enderror"
            placeholder="Keterangan tambahan (opsional)">{{ old('deskripsi', $bahanBaku->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
