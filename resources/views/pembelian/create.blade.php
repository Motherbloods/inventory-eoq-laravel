@extends('layouts.app')
@section('title', 'Tambah Pembelian')
@section('page-title', 'Tambah Pembelian')
@section('page-subtitle', 'Transaksi → Pembelian')

@section('content')
    <form action="{{ route('pembelian.store') }}" method="POST" id="formPembelian">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><span class="card-title">Informasi Pembelian</span></div>
                    <div class="card-body row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Pemasok <span class="text-danger">*</span></label>
                            <select name="pemasok_id" class="form-select @error('pemasok_id') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Pemasok --</option>
                                @foreach ($pemasoks as $p)
                                    <option value="{{ $p->id }}" {{ old('pemasok_id') == $p->id ? 'selected' : '' }}>
                                        {{ $p->nama_pemasok }}</option>
                                @endforeach
                            </select>
                            @error('pemasok_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Pembelian <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pembelian"
                                value="{{ old('tanggal_pembelian', now()->toDateString()) }}"
                                class="form-control @error('tanggal_pembelian') is-invalid @enderror" required>
                            @error('tanggal_pembelian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" value="{{ old('keterangan') }}" class="form-control"
                                placeholder="Catatan tambahan (opsional)">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="card-title">Daftar Bahan Baku</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Baris
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="itemContainer"></div>
                        <div class="d-flex justify-content-end mt-3 pt-3 border-top">
                            <div class="text-end">
                                <div class="text-muted small">Total Pembelian</div>
                                <div class="fs-4 fw-bold text-primary" id="grandTotal">Rp 0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white"><i class="bi bi-save me-1"></i>Simpan
                    Transaksi</button>
                <a href="{{ route('pembelian.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            const bahanBakus = @json($bahanBakus);
            let rowIndex = 0;

            function addRow(data = {}) {
                const idx = rowIndex++;
                const options = bahanBakus.map(b =>
                    `<option value="${b.id}" data-harga="${b.harga_satuan}" ${data.bahan_baku_id == b.id ? 'selected' : ''}>${b.nama_bahan} (${b.satuan})</option>`
                ).join('');

                const html = `
    <div class="item-row" id="row-${idx}">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small">Bahan Baku <span class="text-danger">*</span></label>
                <select name="items[${idx}][bahan_baku_id]" class="form-select form-select-sm bahan-select" onchange="fillHarga(this, ${idx})" required>
                    <option value="">-- Pilih Bahan --</option>${options}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Jumlah <span class="text-danger">*</span></label>
                <input type="number" name="items[${idx}][jumlah]" value="${data.jumlah || ''}"
                       class="form-control form-control-sm jumlah-input" min="0.01" step="0.01"
                       oninput="calcRow(${idx})" placeholder="0" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small">Harga Satuan (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="items[${idx}][harga_satuan]" value="${data.harga_satuan || ''}"
                       class="form-control form-control-sm harga-input" id="harga-${idx}"
                       min="0" step="0.01" oninput="calcRow(${idx})" placeholder="0" required>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeRow(${idx})">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="col-12">
                <div class="text-end small text-muted">Subtotal: <strong id="subtotal-${idx}" class="text-dark">Rp 0</strong></div>
            </div>
        </div>
    </div>`;
                document.getElementById('itemContainer').insertAdjacentHTML('beforeend', html);
                if (data.bahan_baku_id) calcRow(idx);
            }

            function fillHarga(sel, idx) {
                const opt = sel.options[sel.selectedIndex];
                document.getElementById(`harga-${idx}`).value = opt.dataset.harga || '';
                calcRow(idx);
            }

            function calcRow(idx) {
                const row = document.getElementById(`row-${idx}`);
                const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
                const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
                const sub = jumlah * harga;
                document.getElementById(`subtotal-${idx}`).textContent = 'Rp ' + sub.toLocaleString('id-ID');
                calcTotal();
            }

            function calcTotal() {
                let total = 0;
                document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                    total += parseFloat(el.textContent.replace(/[^0-9]/g, '')) || 0;
                });
                document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            }

            function removeRow(idx) {
                document.getElementById(`row-${idx}`)?.remove();
                calcTotal();
            }

            // Init with one row
            addRow();
        </script>
    @endpush
@endsection
