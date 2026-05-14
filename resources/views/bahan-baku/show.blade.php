@extends('layouts.app')
@section('title', $bahanBaku->nama_bahan)
@section('page-title', $bahanBaku->nama_bahan)
@section('page-subtitle', 'Detail Bahan Baku')

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">Informasi Bahan</span>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('bahan-baku.edit', $bahanBaku) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil"></i>
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <dl class="row mb-0" style="font-size:0.875rem">
                        <dt class="col-5 text-muted">Kode</dt>
                        <dd class="col-7"><code>{{ $bahanBaku->kode_bahan }}</code></dd>
                        <dt class="col-5 text-muted">Kategori</dt>
                        <dd class="col-7"><span class="badge bg-light text-dark border">{{ $bahanBaku->kategori }}</span>
                        </dd>
                        <dt class="col-5 text-muted">Satuan</dt>
                        <dd class="col-7">{{ $bahanBaku->satuan }}</dd>
                        <dt class="col-5 text-muted">Harga</dt>
                        <dd class="col-7">Rp {{ number_format($bahanBaku->harga_satuan, 0, ',', '.') }}</dd>
                        <dt class="col-5 text-muted">Stok Saat Ini</dt>
                        <dd class="col-7 fw-bold {{ $bahanBaku->isBawahMinimum() ? 'text-danger' : 'text-success' }}">
                            {{ number_format($bahanBaku->stok_saat_ini, 2, ',', '.') }} {{ $bahanBaku->satuan }}
                        </dd>
                        <dt class="col-5 text-muted">Stok Minimum</dt>
                        <dd class="col-7">{{ number_format($bahanBaku->stok_minimum, 2, ',', '.') }}
                            {{ $bahanBaku->satuan }}</dd>
                        @if ($bahanBaku->deskripsi)
                            <dt class="col-5 text-muted">Deskripsi</dt>
                            <dd class="col-7">{{ $bahanBaku->deskripsi }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if ($bahanBaku->eoqSetting)
                @php $eoq = $bahanBaku->eoqSetting; @endphp
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="card-title"><i class="bi bi-calculator me-1"></i>Parameter & Hasil EOQ</span>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('eoq.setting') }}" class="btn btn-sm btn-outline-secondary"
                                style="font-size:0.72rem">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="px-3 pt-3 pb-2 border-bottom">
                            <div class="text-muted small fw-semibold mb-2"
                                style="font-size:0.72rem;letter-spacing:.04em;text-transform:uppercase">Parameter Input
                            </div>
                            <dl class="row mb-0" style="font-size:0.83rem">
                                <dt class="col-7 text-muted">Permintaan Tahunan (D)</dt>
                                <dd class="col-5 text-end">{{ number_format($eoq->permintaan_tahunan, 2, ',', '.') }}
                                    {{ $bahanBaku->satuan }}</dd>
                                <dt class="col-7 text-muted">Biaya Pemesanan (S)</dt>
                                <dd class="col-5 text-end">Rp {{ number_format($eoq->biaya_pemesanan, 0, ',', '.') }}</dd>
                                <dt class="col-7 text-muted">Biaya Penyimpanan (H)</dt>
                                <dd class="col-5 text-end">Rp {{ number_format($eoq->biaya_penyimpanan, 0, ',', '.') }}
                                </dd>
                                <dt class="col-7 text-muted">Lead Time</dt>
                                <dd class="col-5 text-end">{{ $eoq->lead_time_hari }} hari</dd>
                                <dt class="col-7 text-muted">Tingkat Layanan</dt>
                                <dd class="col-5 text-end">{{ number_format($eoq->service_level, 0) }}%</dd>
                                <dt class="col-7 text-muted">Std. Deviasi Harian (σ)</dt>
                                <dd class="col-5 text-end">
                                    {{ $eoq->std_dev_permintaan > 0 ? number_format($eoq->std_dev_permintaan, 4, ',', '.') : '—' }}
                                </dd>
                            </dl>
                        </div>

                        <div class="px-3 pt-2 pb-3">
                            <div class="text-muted small fw-semibold mb-2"
                                style="font-size:0.72rem;letter-spacing:.04em;text-transform:uppercase">Hasil Perhitungan
                            </div>

                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-2"
                                style="background:#e6f4ff">
                                <div>
                                    <div class="fw-bold text-primary" style="font-size:1rem">
                                        {{ number_format($eoq->eoq_result, 2, ',', '.') }} {{ $bahanBaku->satuan }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.72rem">Q* — Jumlah Pemesanan Optimal</div>
                                </div>
                                <i class="bi bi-cart-plus text-primary fs-4"></i>
                            </div>

                            <div class="d-flex align-items-center justify-content-between p-2 rounded mb-2"
                                style="background:{{ $eoq->dibawahSafetyStock() ? '#fef3c7' : '#fef9ec' }};
                                border:1px solid {{ $eoq->dibawahSafetyStock() ? '#fbbf24' : '#fde68a' }}">
                                <div>
                                    <div class="fw-bold" style="font-size:1rem;color:#d97706">
                                        {{ $eoq->safety_stock > 0 ? number_format($eoq->safety_stock, 2, ',', '.') . ' ' . $bahanBaku->satuan : '0 (σ belum diisi)' }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.72rem">Safety Stock — Stok Cadangan Buffer
                                    </div>
                                </div>
                                <i class="bi bi-shield-check fs-4" style="color:#d97706"></i>
                            </div>
                            @if ($eoq->dibawahSafetyStock())
                                <div class="alert py-1 px-2 mb-2"
                                    style="background:#fef3c7;border:1px solid #fbbf24;border-radius:6px;font-size:0.75rem">
                                    <i class="bi bi-exclamation-triangle-fill me-1" style="color:#d97706"></i>
                                    <strong style="color:#92400e">Stok saat ini sudah di bawah safety stock!</strong>
                                    Kondisi kritis — segera lakukan pemesanan.
                                </div>
                            @endif

                            <div class="d-flex align-items-center justify-content-between p-2 rounded"
                                style="background:{{ $eoq->perluDipesan() ? '#ffeaea' : '#f0fdf4' }};
                                border:1px solid {{ $eoq->perluDipesan() ? '#fca5a5' : '#86efac' }}">
                                <div>
                                    <div class="fw-bold {{ $eoq->perluDipesan() ? 'text-danger' : 'text-success' }}"
                                        style="font-size:1rem">
                                        {{ number_format($eoq->reorder_point, 2, ',', '.') }} {{ $bahanBaku->satuan }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.72rem">ROP — Titik Pemesanan Kembali</div>
                                </div>
                                <i
                                    class="bi bi-arrow-repeat fs-4 {{ $eoq->perluDipesan() ? 'text-danger' : 'text-success' }}"></i>
                            </div>
                            @if ($eoq->perluDipesan())
                                <div class="alert py-1 px-2 mt-2 mb-0"
                                    style="background:#ffeaea;border:1px solid #fca5a5;border-radius:6px;font-size:0.75rem">
                                    <i class="bi bi-bell-fill text-danger me-1"></i>
                                    <strong class="text-danger">Stok menyentuh Reorder Point!</strong>
                                    Pesan segera sebanyak <strong>{{ number_format($eoq->eoq_result, 2, ',', '.') }}
                                        {{ $bahanBaku->satuan }}</strong>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center text-muted py-4 small">
                        <i class="bi bi-calculator fs-3 d-block mb-1"></i>
                        Parameter EOQ belum diisi untuk bahan ini.
                        @if (auth()->user()->isAdmin())
                            <div class="mt-2">
                                <a href="{{ route('eoq.setting') }}" class="btn btn-sm btn-outline-primary">Isi Parameter
                                    EOQ</a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><span class="card-title">Riwayat Stok</span></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Jenis</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $r)
                                <tr>
                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($r['tanggal'])->format('d M Y') }}</td>
                                    <td class="small">{{ $r['keterangan'] }}</td>
                                    <td>
                                        @if ($r['jenis'] === 'masuk')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="bi bi-arrow-down me-1"></i>Masuk
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                <i class="bi bi-arrow-up me-1"></i>Keluar
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="text-end fw-semibold {{ $r['jenis'] === 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $r['jenis'] === 'masuk' ? '+' : '-' }}{{ number_format($r['jumlah'], 2, ',', '.') }}
                                        {{ $bahanBaku->satuan }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4 small">Belum ada riwayat
                                        transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
