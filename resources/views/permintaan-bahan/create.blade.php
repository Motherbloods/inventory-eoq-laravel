@extends('layouts.app')
@section('title', 'Ajukan Permintaan Bahan')
@section('page-title', 'Ajukan Permintaan Bahan')
@section('page-subtitle', 'Permintaan → Buat Permintaan Baru')

@section('content')
    <form action="{{ route('permintaan-bahan.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><span class="card-title">Informasi Permintaan</span></div>
                    <div class="card-body">
                        <div class="col-md-8">
                            <label class="form-label">Catatan / Keperluan</label>
                            <input type="text" name="catatan" value="{{ old('catatan') }}" class="form-control"
                                placeholder="Misal: Untuk produksi roti tawar pesanan hajatan Sabtu">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="card-title">Bahan yang Diminta</span>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addRow()">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Bahan
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="itemContainer"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white"><i class="bi bi-send me-1"></i>Kirim
                    Permintaan</button>
                <a href="{{ route('permintaan-bahan.index') }}" class="btn btn-outline-secondary">Batal</a>
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
                    `<option value="${b.id}" data-stok="${b.stok_saat_ini}" data-satuan="${b.satuan}" ${data.bahan_baku_id == b.id ? 'selected':''}>${b.nama_bahan} — stok: ${parseFloat(b.stok_saat_ini).toLocaleString('id-ID')} ${b.satuan}</option>`
                ).join('');

                const html = `
    <div class="item-row" id="row-${idx}">
        <div class="row g-2 align-items-end">
            <div class="col-md-7">
                <label class="form-label small">Bahan Baku <span class="text-danger">*</span></label>
                <select name="items[${idx}][bahan_baku_id]" class="form-select form-select-sm" onchange="showInfo(this,${idx})" required>
                    <option value="">-- Pilih Bahan --</option>${options}
                </select>
                <div id="info-${idx}" class="form-text"></div>
            </div>
            <div class="col-md-4">
                <label class="form-label small">Jumlah Diminta <span class="text-danger">*</span></label>
                <input type="number" name="items[${idx}][jumlah_diminta]"
                       value="${data.jumlah_diminta || ''}"
                       class="form-control form-control-sm" min="0.01" step="0.01" placeholder="0" required>
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
            }

            function showInfo(sel, idx) {
                const opt = sel.options[sel.selectedIndex];
                const stok = parseFloat(opt.dataset.stok) || 0;
                const satuan = opt.dataset.satuan || '';
                const info = document.getElementById(`info-${idx}`);
                if (opt.value) {
                    const cls = stok <= 0 ? 'text-danger' : (stok < 5 ? 'text-warning' : 'text-success');
                    info.innerHTML = `Stok tersedia: <strong class="${cls}">${stok.toLocaleString('id-ID')} ${satuan}</strong>`;
                } else {
                    info.innerHTML = '';
                }
            }

            addRow();
        </script>
    @endpush
@endsection
