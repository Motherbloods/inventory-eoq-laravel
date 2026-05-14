@extends('layouts.app')
@section('title', 'Parameter EOQ')
@section('page-title', 'Parameter EOQ & Safety Stock')
@section('page-subtitle', 'Pengaturan parameter perhitungan Economic Order Quantity dan Safety Stock')

@section('content')

    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('eoq.hitungSemua') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-calculator me-1"></i>Hitung Ulang Semua
            </button>
        </form>
    </div>

    <div class="alert alert-light border mb-3" style="font-size:0.82rem">
        <div class="row g-2">
            <div class="col-md-4">
                <strong>EOQ (Q*)</strong> = √(2DS / H)
                <div class="text-muted">Jumlah optimal per sekali pesan</div>
            </div>
            <div class="col-md-4">
                <strong>Safety Stock</strong> = Z × σ × √(Lead Time)
                <div class="text-muted">Buffer stok cadangan dari ketidakpastian</div>
            </div>
            <div class="col-md-4">
                <strong>ROP</strong> = (D/365 × Lead Time) + Safety Stock
                <div class="text-muted">Titik stok saat pemesanan harus dilakukan</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span class="card-title">Pengaturan Parameter per Bahan Baku</span>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari nama bahan...">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-sm btn-primary text-white w-100">Cari</button>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:0.82rem">
                    <thead>
                        <tr>
                            <th style="min-width:150px">Bahan Baku</th>
                            <th class="text-end" title="Permintaan Tahunan">D</th>
                            <th class="text-end" title="Biaya Pemesanan">S (Rp)</th>
                            <th class="text-end" title="Biaya Penyimpanan">H (Rp)</th>
                            <th class="text-center" title="Lead Time (hari)">LT</th>
                            <th class="text-center" title="Tingkat Layanan (%)">SL (%)</th>
                            <th class="text-end" title="Standar Deviasi Harian (σ)">σ / hari</th>
                            <th class="text-end text-primary" title="EOQ Result">Q*</th>
                            <th class="text-end text-warning" title="Safety Stock">Safety Stock</th>
                            <th class="text-end text-danger" title="Reorder Point">ROP</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahanBakus as $bahan)
                            @php $eoq = $bahan->eoqSetting; @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold" style="font-size:0.83rem">{{ $bahan->nama_bahan }}</div>
                                    <div class="text-muted" style="font-size:0.7rem">{{ $bahan->kode_bahan }} &bull;
                                        {{ $bahan->satuan }}</div>
                                </td>

                                <form action="{{ route('eoq.storeSetting') }}" method="POST"
                                    id="form-eoq-{{ $bahan->id }}">
                                    @csrf
                                    <input type="hidden" name="bahan_baku_id" value="{{ $bahan->id }}">

                                    <td style="padding:0.35rem 0.5rem">
                                        <input type="number" name="permintaan_tahunan" form="form-eoq-{{ $bahan->id }}"
                                            value="{{ $eoq?->permintaan_tahunan ?? '' }}"
                                            class="form-control form-control-sm text-end" style="min-width:80px"
                                            min="0.01" step="0.01" required>
                                    </td>
                                    <td style="padding:0.35rem 0.5rem">
                                        <input type="number" name="biaya_pemesanan" form="form-eoq-{{ $bahan->id }}"
                                            value="{{ $eoq?->biaya_pemesanan ?? '' }}"
                                            class="form-control form-control-sm text-end" style="min-width:80px"
                                            min="0.01" step="0.01" required>
                                    </td>
                                    <td style="padding:0.35rem 0.5rem">
                                        <input type="number" name="biaya_penyimpanan" form="form-eoq-{{ $bahan->id }}"
                                            value="{{ $eoq?->biaya_penyimpanan ?? '' }}"
                                            class="form-control form-control-sm text-end" style="min-width:80px"
                                            min="0.01" step="0.01" required>
                                    </td>
                                    <td style="padding:0.35rem 0.5rem">
                                        <input type="number" name="lead_time_hari" form="form-eoq-{{ $bahan->id }}"
                                            value="{{ $eoq?->lead_time_hari ?? 1 }}"
                                            class="form-control form-control-sm text-center"
                                            style="min-width:55px;max-width:65px;margin:auto" min="1" max="365"
                                            required>
                                    </td>
                                    <td style="padding:0.35rem 0.5rem">
                                        <select name="service_level" form="form-eoq-{{ $bahan->id }}"
                                            class="form-select form-select-sm text-center" style="min-width:80px"
                                            required>
                                            @foreach ([80 => '80%', 85 => '85%', 90 => '90%', 95 => '95%', 97 => '97%', 98 => '98%', 99 => '99%'] as $val => $label)
                                                <option value="{{ $val }}"
                                                    {{ (int) ($eoq?->service_level ?? 95) === $val ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="padding:0.35rem 0.5rem">
                                        <div class="d-flex gap-1 align-items-center">
                                            <input type="number" name="std_dev_permintaan"
                                                id="stddev-{{ $bahan->id }}" form="form-eoq-{{ $bahan->id }}"
                                                value="{{ $eoq?->std_dev_permintaan ?? 0 }}"
                                                class="form-control form-control-sm text-end" style="min-width:75px"
                                                min="0" step="0.0001" required>
                                            <button type="button" class="btn btn-sm btn-outline-secondary px-1 py-0"
                                                title="Hitung σ otomatis dari histori pemakaian"
                                                onclick="hitungStdDev({{ $bahan->id }}, '{{ $bahan->nama_bahan }}')">
                                                <i class="bi bi-magic" style="font-size:0.75rem"></i>
                                            </button>
                                        </div>
                                        <div id="stddev-info-{{ $bahan->id }}" class="text-muted"
                                            style="font-size:0.65rem;margin-top:2px"></div>
                                    </td>
                                </form>

                                <td class="text-end fw-bold text-primary" style="font-size:0.82rem">
                                    {{ $eoq?->eoq_result ? number_format($eoq->eoq_result, 2, ',', '.') : '—' }}
                                </td>
                                <td class="text-end fw-bold" style="font-size:0.82rem;color:#d97706">
                                    @if ($eoq?->safety_stock !== null)
                                        {{ number_format($eoq->safety_stock, 2, ',', '.') }}
                                        @if ($eoq->safety_stock == 0)
                                            <div class="text-muted fw-normal" style="font-size:0.65rem">σ = 0</div>
                                        @endif
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold"
                                    style="font-size:0.82rem;color:{{ $eoq && $bahan->stok_saat_ini <= ($eoq->reorder_point ?? 0) ? '#dc3545' : '#d97706' }}">
                                    {{ $eoq?->reorder_point ? number_format($eoq->reorder_point, 2, ',', '.') : '—' }}
                                </td>

                                <td>
                                    <button type="submit" form="form-eoq-{{ $bahan->id }}"
                                        class="btn btn-sm btn-outline-primary" title="Simpan & Hitung">
                                        <i class="bi bi-save"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">Tidak ada bahan baku.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($bahanBakus->hasPages())
            <div class="card-body pt-2">{{ $bahanBakus->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    <div class="card mt-3">
        <div class="card-header"><span class="card-title small">Referensi Tingkat Layanan (Service Level) & Z-score</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0" style="font-size:0.8rem">
                <thead>
                    <tr>
                        <th>Service Level</th>
                        <th>Z-score</th>
                        <th>Artinya</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>80%</td>
                        <td class="fw-semibold">0.84</td>
                        <td class="text-muted">Dari 100 periode, 80 kali stok cukup. Risiko kehabisan cukup tinggi.</td>
                    </tr>
                    <tr class="table-light">
                        <td>90%</td>
                        <td class="fw-semibold">1.28</td>
                        <td class="text-muted">90 dari 100 periode aman. Cocok untuk bahan yang mudah dicari pengganti.
                        </td>
                    </tr>
                    <tr>
                        <td>95%</td>
                        <td class="fw-semibold">1.65</td>
                        <td class="text-muted">Standar umum industri. 95 dari 100 periode terpenuhi tanpa kehabisan.</td>
                    </tr>
                    <tr class="table-light">
                        <td>97%</td>
                        <td class="fw-semibold">1.88</td>
                        <td class="text-muted">Lebih konservatif. Cocok untuk bahan kritis yang susah didapat.</td>
                    </tr>
                    <tr>
                        <td>99%</td>
                        <td class="fw-semibold">2.33</td>
                        <td class="text-muted">Sangat aman tapi stok cadangan besar. Biaya simpan tinggi.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            async function hitungStdDev(bahanId, namaBahan) {
                const infoEl = document.getElementById(`stddev-info-${bahanId}`);
                const inputEl = document.getElementById(`stddev-${bahanId}`);

                infoEl.textContent = 'Menghitung...';
                infoEl.style.color = '#888';

                try {
                    const res = await fetch(`/eoq/std-dev/${bahanId}?hari=90`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await res.json();

                    if (data.std_dev > 0) {
                        inputEl.value = data.std_dev;
                        infoEl.textContent = `σ = ${data.std_dev} (90 hari)`;
                        infoEl.style.color = '#198754';
                    } else {
                        infoEl.textContent = 'Data histori kurang, isi manual';
                        infoEl.style.color = '#dc3545';
                    }
                } catch (e) {
                    infoEl.textContent = 'Gagal menghitung';
                    infoEl.style.color = '#dc3545';
                }
            }
        </script>
    @endpush
@endsection
