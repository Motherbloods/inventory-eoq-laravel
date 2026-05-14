@extends('layouts.app')
@section('title', 'Hasil EOQ & Safety Stock')
@section('page-title', 'Hasil EOQ, Safety Stock & Reorder Point')
@section('page-subtitle', 'Rekomendasi jumlah pemesanan, stok cadangan, dan titik pemesanan kembali')

@section('content')

    @php
        $totalEoq = $eoqResults->total();
        $perluDipesan = $eoqResults
            ->filter(fn($e) => $e->bahanBaku->stok_saat_ini <= ($e->reorder_point ?? 0))
            ->count();
        $bawahSafetyStock = $eoqResults
            ->filter(fn($e) => $e->safety_stock > 0 && $e->bahanBaku->stok_saat_ini < $e->safety_stock)
            ->count();
    @endphp
    <div class="row g-3 mb-3">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div
                        style="width:40px;height:40px;background:#e6f4ff;border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-calculator text-primary fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4">{{ $totalEoq }}</div>
                        <div class="text-muted small">Bahan dengan EOQ</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div
                        style="width:40px;height:40px;background:#fff0e6;border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-arrow-repeat text-warning fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 text-warning">{{ $perluDipesan }}</div>
                        <div class="text-muted small">Perlu Dipesan (≤ ROP)</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="card" style="border-left:3px solid #f59e0b">
                <div class="card-body d-flex align-items-center gap-3">
                    <div
                        style="width:40px;height:40px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-shield-exclamation" style="color:#d97706;font-size:1.2rem"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4" style="color:#d97706">{{ $bawahSafetyStock }}</div>
                        <div class="text-muted small">Di Bawah Safety Stock</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="card-title me-auto">Hasil Perhitungan EOQ & Safety Stock</span>
            <div class="d-flex gap-2 flex-wrap">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari bahan..." style="width:170px">
                    <select name="filter" class="form-select form-select-sm" style="width:180px">
                        <option value="">Semua Bahan</option>
                        <option value="perlu_dipesan" {{ request('filter') == 'perlu_dipesan' ? 'selected' : '' }}>Perlu
                            Dipesan (≤ ROP)</option>
                        <option value="bawah_safety" {{ request('filter') == 'bawah_safety' ? 'selected' : '' }}>Di Bawah
                            Safety Stock</option>
                    </select>
                    <button class="btn btn-sm btn-outline-secondary">Cari</button>
                </form>
                <a href="{{ route('laporan.reorder') }}" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-file-earmark-text me-1"></i>Laporan Reorder
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0" style="font-size:0.82rem">
                    <thead>
                        <tr>
                            <th style="min-width:170px">Bahan Baku</th>
                            <th class="text-end">Stok Saat Ini</th>
                            <th class="text-end">SL</th>
                            <th class="text-end">σ / hari</th>
                            <th class="text-end">Lead Time</th>
                            <th class="text-end text-primary">Q* (EOQ)</th>
                            <th class="text-end" style="color:#d97706">Safety Stock</th>
                            <th class="text-end text-danger">ROP</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eoqResults as $e)
                            @php
                                $bawahSS = $e->safety_stock > 0 && $e->bahanBaku->stok_saat_ini < $e->safety_stock;
                                $perluPesan = $e->bahanBaku->stok_saat_ini <= ($e->reorder_point ?? 0);
                                $rowBg = $bawahSS ? 'table-warning' : ($perluPesan ? '' : '');
                            @endphp
                            <tr class="{{ $rowBg }}">
                                <td>
                                    <a href="{{ route('bahan-baku.show', $e->bahanBaku) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $e->bahanBaku->nama_bahan }}
                                    </a>
                                    <div class="text-muted" style="font-size:0.7rem">{{ $e->bahanBaku->kode_bahan }} &bull;
                                        {{ $e->bahanBaku->satuan }}</div>
                                </td>
                                <td
                                    class="text-end fw-bold {{ $e->bahanBaku->isBawahMinimum() ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($e->bahanBaku->stok_saat_ini, 2, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    <span
                                        class="badge bg-light text-dark border">{{ number_format($e->service_level, 0) }}%</span>
                                </td>
                                <td class="text-end text-muted">
                                    {{ $e->std_dev_permintaan > 0 ? number_format($e->std_dev_permintaan, 4, ',', '.') : '—' }}
                                </td>
                                <td class="text-end text-muted">{{ $e->lead_time_hari }} hari</td>
                                <td class="text-end fw-bold text-primary">{{ number_format($e->eoq_result, 2, ',', '.') }}
                                </td>

                                <td class="text-end fw-bold" style="color:#d97706">
                                    @if ($e->safety_stock > 0)
                                        {{ number_format($e->safety_stock, 2, ',', '.') }}
                                        @if ($bawahSS)
                                            <div style="font-size:0.65rem;color:#dc3545;font-weight:600">⚠ Stok di bawah
                                                buffer</div>
                                        @endif
                                    @else
                                        <span class="text-muted">0 <span style="font-size:0.7rem">(σ=0)</span></span>
                                    @endif
                                </td>

                                <td class="text-end fw-bold {{ $perluPesan ? 'text-danger' : 'text-warning' }}">
                                    {{ number_format($e->reorder_point, 2, ',', '.') }}
                                </td>

                                <td>
                                    @if ($bawahSS)
                                        <span class="badge" style="background:#d97706;color:#fff">
                                            <i class="bi bi-shield-exclamation me-1"></i>Bawah Safety Stock
                                        </span>
                                    @elseif($perluPesan)
                                        <span class="badge bg-danger">
                                            <i class="bi bi-arrow-repeat me-1"></i>Pesan Sekarang
                                        </span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Belum ada data EOQ. Isi parameter di menu Parameter EOQ terlebih dahulu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($eoqResults->hasPages())
            <div class="card-body pt-2">{{ $eoqResults->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    <div class="alert alert-light border mt-3 small">
        <div class="row g-2">
            <div class="col-md-4">
                <strong class="text-primary">Q* (EOQ)</strong> — Jumlah yang paling ekonomis untuk dipesan setiap kali
                melakukan pemesanan.
            </div>
            <div class="col-md-4">
                <strong style="color:#d97706">Safety Stock</strong> — Stok cadangan buffer dari ketidakpastian pemakaian.
                Jika stok saat ini sudah di bawah nilai ini, kondisi sudah sangat kritis.
            </div>
            <div class="col-md-4">
                <strong class="text-danger">ROP</strong> — Reorder Point. Titik stok dimana pemesanan harus segera
                dilakukan. ROP sudah memperhitungkan safety stock di dalamnya.
            </div>
        </div>
    </div>
@endsection
