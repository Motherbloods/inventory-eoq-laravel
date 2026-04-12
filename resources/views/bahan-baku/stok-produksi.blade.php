@extends('layouts.app')
@section('title', 'Ketersediaan Bahan')
@section('page-title', 'Ketersediaan Bahan')
@section('page-subtitle', 'Lihat stok bahan baku yang tersedia saat ini')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span class="card-title me-auto">Stok Bahan Baku</span>
            <a href="#" class="btn btn-sm btn-primary text-white">
                <i class="bi bi-plus-lg me-1"></i>Ajukan Permintaan
            </a>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-5">
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
                    <button class="btn btn-sm btn-primary text-white flex-fill">Cari</button>
                    <a href="{{ route('stok-bahan') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama Bahan</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Stok Tersedia</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanBakus as $b)
                        <tr>
                            <td class="fw-semibold">{{ $b->nama_bahan }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $b->kategori }}</span></td>
                            <td class="small">{{ $b->satuan }}</td>
                            <td class="text-end fw-bold {{ $b->isBawahMinimum() ? 'stok-kritis' : 'stok-aman' }}">
                                {{ number_format($b->stok_saat_ini, 2, ',', '.') }}
                            </td>
                            <td>
                                @if ($b->stok_saat_ini <= 0)
                                    <span class="badge bg-danger">Habis</span>
                                @elseif($b->isBawahMinimum())
                                    <span class="badge bg-warning text-dark">Hampir Habis</span>
                                @else
                                    <span class="badge bg-success">Tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Tidak ada data bahan baku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bahanBakus->hasPages())
            <div class="card-body pt-2">{{ $bahanBakus->links() }}</div>
        @endif
    </div>
@endsection
