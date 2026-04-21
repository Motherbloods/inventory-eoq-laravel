@extends('layouts.app')
@section('title', 'Laporan Perlu Dipesan')
@section('page-title', 'Bahan Perlu Dipesan')
@section('page-subtitle', 'Daftar bahan yang mencapai batas minimum atau Reorder Point EOQ')

@section('content')
    <div class="d-flex justify-content-end gap-2 mb-3">
        <a href="{{ route('laporan.export', ['type' => 'reorder', 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
        <a href="{{ route('laporan.export', ['type' => 'reorder', 'format' => 'excel']) }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-file-excel me-1"></i>Export Excel
        </a>
    </div>

    {{-- Seksi 1: Bawah Stok Minimum --}}
    <div class="card mb-3">
        <div class="card-header">
            <span class="card-title text-danger">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Bahan di Bawah Stok Minimum ({{ $bahanMinimum->count() }})
            </span>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bahan Baku</th>
                        <th>Kategori</th>
                        <th class="text-end">Stok Min</th>
                        <th class="text-end">Stok Saat Ini</th>
                        <th class="text-end">Kekurangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanMinimum as $i => $b)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td>
                                <a href="{{ route('bahan-baku.show', $b) }}"
                                    class="text-decoration-none fw-semibold">{{ $b->nama_bahan }}</a>
                                <div class="text-muted small">{{ $b->kode_bahan }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $b->kategori }}</span></td>
                            <td class="text-end small">{{ number_format($b->stok_minimum, 2, ',', '.') }} {{ $b->satuan }}
                            </td>
                            <td class="text-end fw-bold text-danger">{{ number_format($b->stok_saat_ini, 2, ',', '.') }}
                                {{ $b->satuan }}</td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($b->stok_minimum - $b->stok_saat_ini, 2, ',', '.') }} {{ $b->satuan }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-success py-3">
                                <i class="bi bi-check-circle me-1"></i>Semua bahan masih di atas stok minimum.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Seksi 2: EOQ Reorder Point --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title text-warning">
                <i class="bi bi-arrow-repeat me-1"></i>
                Bahan Mencapai Reorder Point EOQ ({{ $bahanReorderEoq->count() }})
            </span>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Bahan Baku</th>
                        <th class="text-end">Stok Saat Ini</th>
                        <th class="text-end">Reorder Point</th>
                        <th class="text-end">Q* (EOQ)</th>
                        <th>Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanReorderEoq as $i => $e)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td>
                                <a href="{{ route('bahan-baku.show', $e->bahanBaku) }}"
                                    class="text-decoration-none fw-semibold">{{ $e->bahanBaku->nama_bahan }}</a>
                                <div class="text-muted small">{{ $e->bahanBaku->kode_bahan }}</div>
                            </td>
                            <td class="text-end fw-bold text-danger">
                                {{ number_format($e->bahanBaku->stok_saat_ini, 2, ',', '.') }} {{ $e->bahanBaku->satuan }}
                            </td>
                            <td class="text-end text-warning fw-semibold">{{ number_format($e->reorder_point, 2, ',', '.') }}
                                {{ $e->bahanBaku->satuan }}</td>
                            <td class="text-end fw-bold text-primary">{{ number_format($e->eoq_result, 2, ',', '.') }}
                                {{ $e->bahanBaku->satuan }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    Pesan {{ number_format($e->eoq_result, 2, ',', '.') }} {{ $e->bahanBaku->satuan }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-success py-3">
                                <i class="bi bi-check-circle me-1"></i>Tidak ada bahan yang mencapai reorder point.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
