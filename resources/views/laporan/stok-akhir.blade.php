@extends('layouts.app')
@section('title', 'Laporan Stok Akhir')
@section('page-title', 'Laporan Stok Akhir')
@section('page-subtitle', 'Laporan kondisi stok seluruh bahan baku saat ini')

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="card-title me-auto">Stok Akhir Bahan Baku</span>
            <a href="{{ route('laporan.export', ['type' => 'stok-akhir', 'format' => 'pdf']) }}"
                class="btn btn-sm btn-outline-danger">
                <i class="bi bi-file-pdf me-1"></i>Export PDF
            </a>
            <a href="{{ route('laporan.export', ['type' => 'stok-akhir', 'format' => 'excel']) }}"
                class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-excel me-1"></i>Export Excel
            </a>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari nama bahan...">
                </div>
                <div class="col-sm-3">
                    <select name="kategori" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k }}" {{ request('kategori') == $k ? 'selected' : '' }}>
                                {{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Filter</button>
                    <a href="{{ route('laporan.stok-akhir') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Bahan</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Stok Minimum</th>
                        <th class="text-end">Stok Saat Ini</th>
                        <th class="text-end">Nilai Stok (Rp)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanBakus as $i => $b)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td><code style="font-size:0.75rem">{{ $b->kode_bahan }}</code></td>
                            <td class="fw-semibold small">{{ $b->nama_bahan }}</td>
                            <td><span class="badge bg-light text-dark border"
                                    style="font-size:0.7rem">{{ $b->kategori }}</span></td>
                            <td class="small">{{ $b->satuan }}</td>
                            <td class="text-end small">{{ number_format($b->stok_minimum, 2, ',', '.') }}</td>
                            <td class="text-end fw-bold {{ $b->isBawahMinimum() ? 'text-danger' : 'text-success' }}">
                                {{ number_format($b->stok_saat_ini, 2, ',', '.') }}
                            </td>
                            <td class="text-end small">
                                Rp {{ number_format($b->stok_saat_ini * $b->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td>
                                @if ($b->isBawahMinimum())
                                    <span class="badge bg-danger">Kritis</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light">
                        <td colspan="7" class="text-end fw-bold">Total Nilai Stok</td>
                        <td class="text-end fw-bold text-primary">
                            Rp
                            {{ number_format($bahanBakus->sum(fn($b) => $b->stok_saat_ini * $b->harga_satuan), 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
