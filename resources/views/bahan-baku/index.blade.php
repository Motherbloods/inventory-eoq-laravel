@extends('layouts.app')
@section('title', 'Bahan Baku')
@section('page-title', 'Bahan Baku')
@section('page-subtitle', 'Data master seluruh bahan baku')

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="card-title me-auto">Daftar Bahan Baku</span>
            @if ($totalKritis > 0)
                <a href="{{ route('bahan-baku.index', ['filter' => 'kritis']) }}" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-exclamation-triangle me-1"></i>{{ $totalKritis }} Stok Kritis
                </a>
            @endif
            @can('admin')
            @endcan
            @if (auth()->user()->isAdmin())
                <a href="{{ route('bahan-baku.create') }}" class="btn btn-sm btn-primary text-white">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Bahan
                </a>
            @endif
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari nama atau kode bahan...">
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
                <div class="col-sm-2">
                    <select name="filter" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="kritis" {{ request('filter') == 'kritis' ? 'selected' : '' }}>Stok Kritis</option>
                    </select>
                </div>
                <div class="col-sm-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Cari</button>
                    <a href="{{ route('bahan-baku.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Bahan</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Stok Saat Ini</th>
                        <th class="text-end">Stok Minimum</th>
                        <th class="text-end">Harga Satuan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanBakus as $bahan)
                        <tr>
                            <td><code style="font-size:0.78rem">{{ $bahan->kode_bahan }}</code></td>
                            <td class="fw-semibold">{{ $bahan->nama_bahan }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $bahan->kategori }}</span></td>
                            <td>{{ $bahan->satuan }}</td>
                            <td class="text-end fw-bold {{ $bahan->isBawahMinimum() ? 'stok-kritis' : 'stok-aman' }}">
                                {{ number_format($bahan->stok_saat_ini, 2, ',', '.') }}
                            </td>
                            <td class="text-end text-muted">{{ number_format($bahan->stok_minimum, 2, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($bahan->harga_satuan, 0, ',', '.') }}</td>
                            <td>
                                @if ($bahan->isBawahMinimum())
                                    <span class="badge bg-danger"><i class="bi bi-exclamation-circle me-1"></i>Kritis</span>
                                @else
                                    <span class="badge bg-success">Aman</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('bahan-baku.show', $bahan) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Detail"><i
                                            class="bi bi-eye"></i></a>
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('bahan-baku.edit', $bahan) }}"
                                            class="btn btn-sm btn-outline-primary" title="Edit"><i
                                                class="bi bi-pencil"></i></a>
                                        <form action="{{ route('bahan-baku.destroy', $bahan) }}" method="POST"
                                            onsubmit="return confirm('Hapus bahan baku ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">Tidak ada data bahan baku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bahanBakus->hasPages())
            <div class="card-body pt-2">{{ $bahanBakus->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
