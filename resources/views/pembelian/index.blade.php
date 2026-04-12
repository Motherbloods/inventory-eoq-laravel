@extends('layouts.app')
@section('title', 'Pembelian Bahan Baku')
@section('page-title', 'Pembelian (Stok Masuk)')
@section('page-subtitle', 'Transaksi → Pembelian')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <span class="card-title me-auto">Daftar Transaksi Pembelian</span>
            <a href="{{ route('pembelian.create') }}" class="btn btn-sm btn-primary text-white">
                <i class="bi bi-plus-lg me-1"></i>Tambah Pembelian
            </a>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="No. Transaksi...">
                </div>
                <div class="col-sm-3">
                    <select name="pemasok_id" class="form-select form-select-sm">
                        <option value="">Semua Pemasok</option>
                        @foreach ($pemasoks as $p)
                            <option value="{{ $p->id }}" {{ request('pemasok_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pemasok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2"><input type="date" name="dari" value="{{ request('dari') }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-2"><input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Cari</button>
                    <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Pemasok</th>
                        <th class="text-end">Total</th>
                        <th>Input Oleh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelians as $p)
                        <tr>
                            <td><span class="fw-semibold small">{{ $p->nomor_transaksi }}</span></td>
                            <td class="small text-muted">{{ $p->tanggal_pembelian->format('d M Y') }}</td>
                            <td class="small">{{ $p->pemasok->nama_pemasok }}</td>
                            <td class="text-end fw-semibold small">Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="small text-muted">{{ $p->user->name }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('pembelian.show', $p) }}" class="btn btn-sm btn-outline-secondary"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="{{ route('pembelian.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i
                                            class="bi bi-pencil"></i></a>
                                    <form action="{{ route('pembelian.destroy', $p) }}" method="POST"
                                        onsubmit="return confirm('Hapus transaksi ini? Stok akan dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada transaksi pembelian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pembelians->hasPages())
            <div class="card-body pt-2">{{ $pembelians->links() }}</div>
        @endif
    </div>
@endsection
