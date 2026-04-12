@extends('layouts.app')
@section('title', 'Edit Pemakaian')
@section('page-title', 'Edit Pemakaian')
@section('page-subtitle', 'Transaksi → Pemakaian')

@section('content')
    <form action="{{ route('pemakaian.update', $pemakaian) }}" method="POST">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><span class="card-title">Informasi Pemakaian</span></div>
                    <div class="card-body row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pemakaian"
                                value="{{ old('tanggal_pemakaian', $pemakaian->tanggal_pemakaian->toDateString()) }}"
                                class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Keterangan</label>
                            <input type="text" name="keterangan" value="{{ old('keterangan', $pemakaian->keterangan) }}"
                                class="form-control">
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
                <button type="submit" class="btn btn-primary text-white"><i class="bi bi-save me-1"></i>Perbarui</button>
                <a href="{{ route('pemakaian.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            const bahanBakus = @json($bahanBakus);
            const existingItems = @json($pemakaian->detail->map(fn($d) => ['bahan_baku_id' => $d->bahan_baku_id, 'jumlah' => $d->jumlah]));
            let rowIndex = 0;

            function addRow(data = {}) {
                const idx = rowIndex++;
                const options = bahanBakus.map(b =>
                    `<option value="${b.id}" data-stok="${b.stok_saat_ini}" data-satuan="${b.satuan}" ${data.bahan_baku_id == b.id ? 'selected' : ''}>${b.nama_bahan} (stok: ${parseFloat(b.stok_saat_ini).toLocaleString('id-ID')} ${b.satuan})</option>`
                ).join('');
                const html = `
    <div class="item-row" id="row-${idx}">
        <div class="row g-2 align-items-end">
            <div class="col-md-7">
                <label class="form-label small">Bahan Baku</label>
                <select name="items[${idx}][bahan_baku_id]" class="form-select form-select-sm" required>
                    <option value="">-- Pilih --</option>${options}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Jumlah</label>
                <input type="number" name="items[${idx}][jumlah]" value="${data.jumlah || ''}" class="form-control form-control-sm" min="0.01" step="0.01" required>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="document.getElementById('row-${idx}').remove()"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    </div>`;
                document.getElementById('itemContainer').insertAdjacentHTML('beforeend', html);
            }

            existingItems.forEach(item => addRow(item));
            if (existingItems.length === 0) addRow();
        </script>
    @endpush
@endsection
