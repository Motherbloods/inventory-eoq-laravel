@extends('layouts.app')
@section('title', 'Tambah Pemakaian')
@section('page-title', 'Tambah Pemakaian')
@section('page-subtitle', 'Transaksi → Pemakaian')

@section('content')
    <form action="{{ route('pemakaian.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><span class="card-title">Informasi Pemakaian</span></div>
                    <div class="card-body row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Pemakaian <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemakaian"
                                value="{{ old('tanggal_pemakaian', now()->toDateString()) }}"
                                class="form-control @error('tanggal_pemakaian') is-invalid @enderror" required>
                            @error('tanggal_pemakaian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="form-control"
                                placeholder="Misal: Produksi roti tawar 200 pcs">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="card-title">Bahan yang Dipakai</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()"><i
                                class="bi bi-plus-lg me-1"></i>Tambah Baris</button>
                    </div>
                    <div class="card-body">
                        <div id="itemContainer"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white"><i class="bi bi-save me-1"></i>Simpan</button>
                <a href="{{ route('pemakaian.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            const bahanBakus = @json($bahanBakus);
            let oldItems = @json(old('items', []));
            let errors = @json($errors->toArray());
            let rowIndex = 0;

            function addRow(data = {}) {
                const idx = rowIndex++;
                const options = bahanBakus.map(b =>
                    `<option value="${b.id}" data-stok="${b.stok_saat_ini}" data-satuan="${b.satuan}" ${data.bahan_baku_id == b.id ? 'selected' : ''}>
            ${b.nama_bahan} — stok: ${parseFloat(b.stok_saat_ini).toLocaleString('id-ID')} ${b.satuan}
        </option>`
                ).join('');

                let errorBahan = errors[`items.${idx}.bahan_baku_id`]?.[0] ?? '';
                let errorJumlah = errors[`items.${idx}.jumlah`]?.[0] ?? '';

                const html = `
    <div class="item-row mb-2" id="row-${idx}">
        <div class="row g-2 align-items-end">
            <div class="col-md-7">
                <label class="form-label small">Bahan Baku <span class="text-danger">*</span></label>
                <select name="items[${idx}][bahan_baku_id]" 
                        class="form-select form-select-sm ${errorBahan ? 'is-invalid' : ''}" 
                        onchange="showStok(this, ${idx})" required>
                    <option value="">-- Pilih Bahan --</option>${options}
                </select>
                <div id="stok-info-${idx}" class="form-text"></div>
                ${errorBahan ? `<div class="invalid-feedback">${errorBahan}</div>` : ''}
            </div>

            <div class="col-md-4">
                <label class="form-label small">Jumlah Dipakai <span class="text-danger">*</span></label>
                <input type="number" 
                       name="items[${idx}][jumlah]" 
                       value="${data.jumlah || ''}"
                       class="form-control form-control-sm ${errorJumlah ? 'is-invalid' : ''}" 
                       min="0.01" step="0.01" placeholder="0" required>
                ${errorJumlah ? `<div class="invalid-feedback">${errorJumlah}</div>` : ''}
            </div>

            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger w-100"
                    onclick="document.getElementById('row-${idx}').remove()">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
    </div>`;

                document.getElementById('itemContainer').insertAdjacentHTML('beforeend', html);

                if (data.bahan_baku_id) {
                    const sel = document.querySelector(`#row-${idx} select`);
                    showStok(sel, idx);
                }
            }

            function showStok(sel, idx) {
                const opt = sel.options[sel.selectedIndex];
                const stok = opt.dataset.stok;
                const satuan = opt.dataset.satuan;
                const info = document.getElementById(`stok-info-${idx}`);
                if (stok !== undefined) {
                    info.innerHTML =
                        `Stok tersedia: <strong class="${parseFloat(stok) <= 0 ? 'text-danger' : 'text-success'}">${parseFloat(stok).toLocaleString('id-ID')} ${satuan}</strong>`;
                } else {
                    info.innerHTML = '';
                }
            }

            if (oldItems.length > 0) {
                oldItems.forEach(item => addRow(item));
            } else {
                addRow();
            }
        </script>
    @endpush
@endsection
