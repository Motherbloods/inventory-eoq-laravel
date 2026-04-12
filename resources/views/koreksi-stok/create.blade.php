@extends('layouts.app')
@section('title', 'Koreksi Stok')
@section('page-title', 'Tambah Koreksi Stok')
@section('page-subtitle', 'Transaksi → Koreksi Stok')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header"><span class="card-title">Form Koreksi Stok</span></div>
                <div class="card-body">
                    <div class="alert alert-warning py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Koreksi stok digunakan untuk menyesuaikan stok akibat selisih fisik. Data ini tidak dapat
                        dihapus/diubah (audit trail).
                    </div>
                    <form action="{{ route('koreksi-stok.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Bahan Baku <span class="text-danger">*</span></label>
                                <select name="bahan_baku_id" id="bahanSelect"
                                    class="form-select @error('bahan_baku_id') is-invalid @enderror" onchange="fillStok()"
                                    required>
                                    <option value="">-- Pilih Bahan Baku --</option>
                                    @foreach ($bahanBakus as $b)
                                        <option value="{{ $b->id }}" data-stok="{{ $b->stok_saat_ini }}"
                                            data-satuan="{{ $b->satuan }}"
                                            {{ old('bahan_baku_id') == $b->id ? 'selected' : '' }}>
                                            {{ $b->nama_bahan }} ({{ $b->stok_saat_ini }} {{ $b->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bahan_baku_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Koreksi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_koreksi"
                                    value="{{ old('tanggal_koreksi', now()->toDateString()) }}"
                                    class="form-control @error('tanggal_koreksi') is-invalid @enderror" required>
                                @error('tanggal_koreksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok Saat Ini (Sistem)</label>
                                <input type="text" id="stokSebelum" class="form-control" readonly
                                    style="background:#f8f9fa">
                                <div class="form-text">Diambil otomatis dari pilihan bahan baku.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stok Aktual (Hasil Cek Fisik) <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="jumlah_sesudah" id="stokSesudah"
                                    value="{{ old('jumlah_sesudah') }}"
                                    class="form-control @error('jumlah_sesudah') is-invalid @enderror" min="0"
                                    step="0.01" oninput="showSelisih()" required>
                                @error('jumlah_sesudah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="selisihInfo" class="form-text fw-semibold"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alasan Koreksi <span class="text-danger">*</span></label>
                                <textarea name="alasan" rows="3" class="form-control @error('alasan') is-invalid @enderror"
                                    placeholder="Jelaskan alasan koreksi (min. 10 karakter). Misal: Hasil opname fisik berbeda karena tumpahan saat produksi.">{{ old('alasan') }}</textarea>
                                @error('alasan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary text-white"><i class="bi bi-save me-1"></i>Simpan
                                Koreksi</button>
                            <a href="{{ route('koreksi-stok.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function fillStok() {
                const sel = document.getElementById('bahanSelect');
                const opt = sel.options[sel.selectedIndex];
                const stok = opt.dataset.stok || '';
                const satuan = opt.dataset.satuan || '';
                document.getElementById('stokSebelum').value = stok ? `${parseFloat(stok).toLocaleString('id-ID')} ${satuan}` :
                    '';
                showSelisih();
            }

            function showSelisih() {
                const sel = document.getElementById('bahanSelect');
                const opt = sel.options[sel.selectedIndex];
                const before = parseFloat(opt.dataset.stok) || 0;
                const after = parseFloat(document.getElementById('stokSesudah').value) || 0;
                const selisih = after - before;
                const info = document.getElementById('selisihInfo');
                if (opt.dataset.stok) {
                    info.textContent =
                        `Selisih: ${selisih >= 0 ? '+' : ''}${selisih.toLocaleString('id-ID', {minimumFractionDigits:2})} ${opt.dataset.satuan || ''}`;
                    info.className = `form-text fw-semibold ${selisih >= 0 ? 'text-success' : 'text-danger'}`;
                } else {
                    info.textContent = '';
                }
            }
        </script>
    @endpush
@endsection
