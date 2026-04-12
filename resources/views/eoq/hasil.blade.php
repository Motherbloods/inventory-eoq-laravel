@extends('layouts.app')
@section('title', 'Hasil EOQ & Reorder Point')
@section('page-title', 'Hasil EOQ & Reorder Point')
@section('page-subtitle', 'Rekomendasi jumlah dan titik pemesanan kembali')

@section('content')

    @php
        $totalEoq = $eoqResults->total();
        $perluDipesan = $eoqResults->filter(fn($e) => $e->bahanBaku->stok_saat_ini <= $e->reorder_point)->count();
    @endphp
    <div class="row g-3 mb-3">
        <div class="col-sm-6 col-md-3">
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
        <div class="col-sm-6 col-md-3">
            <div class="card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div
                        style="width:40px;height:40px;background:#fff0e6;border-radius:8px;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-arrow-repeat text-warning fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-bold fs-4 text-warning">{{ $perluDipesan }}</div>
                        <div class="text-muted small">Perlu Dipesan Ulang</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span class="card-title">Hasil Perhitungan EOQ</span>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari bahan..." style="width:180px">
                    <select name="filter" class="form-select form-select-sm" style="width:160px">
                        <option value="">Semua Bahan</option>
                        <option value="perlu_dipesan" {{ request('filter') == 'perlu_dipesan' ? 'selected' : '' }}>Perlu
                            Dipesan</option>
                    </select>
                    <button class="btn btn-sm btn-outline-secondary">Cari</button>
                </form>
                <a href="#" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-file-earmark-text me-1"></i>Laporan Reorder
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Bahan Baku</th>
                            <th class="text-end">Stok Saat Ini</th>
                            <th class="text-end">D (unit/thn)</th>
                            <th class="text-end">S (Rp)</th>
                            <th class="text-end">H (Rp)</th>
                            <th class="text-end text-primary">Q* (EOQ)</th>
                            <th class="text-end text-warning">ROP</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($eoqResults as $e)
                            @php $perlu = $e->bahanBaku->stok_saat_ini <= $e->reorder_point; @endphp
                            <tr class="{{ $perlu ? 'table-warning bg-opacity-25' : '' }}">
                                <td>
                                    <a href="{{ route('bahan-baku.show', $e->bahanBaku) }}"
                                        class="text-decoration-none fw-semibold">
                                        {{ $e->bahanBaku->nama_bahan }}
                                    </a>
                                    <div class="text-muted small">{{ $e->bahanBaku->kode_bahan }} &bull;
                                        {{ $e->bahanBaku->satuan }}</div>
                                </td>
                                <td
                                    class="text-end fw-bold {{ $e->bahanBaku->isBawahMinimum() ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($e->bahanBaku->stok_saat_ini, 2, ',', '.') }}
                                </td>
                                <td class="text-end small">{{ number_format($e->permintaan_tahunan, 2, ',', '.') }}</td>
                                <td class="text-end small">Rp {{ number_format($e->biaya_pemesanan, 0, ',', '.') }}</td>
                                <td class="text-end small">Rp {{ number_format($e->biaya_penyimpanan, 0, ',', '.') }}</td>
                                <td class="text-end fw-bold text-primary">{{ number_format($e->eoq_result, 2, ',', '.') }}
                                </td>
                                <td class="text-end fw-bold {{ $perlu ? 'text-danger' : 'text-warning' }}">
                                    {{ number_format($e->reorder_point, 2, ',', '.') }}
                                </td>
                                <td>
                                    @if ($perlu)
                                        <span class="badge bg-danger"><i class="bi bi-arrow-repeat me-1"></i>Pesan
                                            Sekarang</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data EOQ. Isi parameter
                                    terlebih dahulu.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($eoqResults->hasPages())
            <div class="card-body pt-2">{{ $eoqResults->links() }}</div>
        @endif
    </div>
@endsection
