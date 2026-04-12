@extends('layouts.app')
@section('title', 'Pemakaian Bahan Baku')
@section('page-title', 'Pemakaian (Stok Keluar)')
@section('page-subtitle', 'Transaksi → Pemakaian')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <span class="card-title me-auto">Daftar Transaksi Pemakaian</span>
            <a href="{{ route('pemakaian.create') }}" class="btn btn-sm btn-primary text-white"><i
                    class="bi bi-plus-lg me-1"></i>Tambah Pemakaian</a>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-4"><input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="No. Transaksi..."></div>
                <div class="col-sm-3"><input type="date" name="dari" value="{{ request('dari') }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-3"><input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Cari</button>
                    <a href="{{ route('pemakaian.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Input Oleh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemakaians as $p)
                        <tr>
                            <td class="fw-semibold small">{{ $p->nomor_transaksi }}</td>
                            <td class="small text-muted">{{ $p->tanggal_pemakaian->format('d M Y') }}</td>
                            <td class="small">{{ $p->keterangan ?? '-' }}</td>
                            <td class="small text-muted">{{ $p->user->name }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('pemakaian.show', $p) }}" class="btn btn-sm btn-outline-secondary"><i
                                            class="bi bi-eye"></i></a>
                                    <a href="{{ route('pemakaian.edit', $p) }}" class="btn btn-sm btn-outline-primary"><i
                                            class="bi bi-pencil"></i></a>
                                    <form action="{{ route('pemakaian.destroy', $p) }}" method="POST"
                                        onsubmit="return confirm('Hapus? Stok akan dikembalikan.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada transaksi pemakaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pemakaians->hasPages())
            <div class="card-body pt-2">{{ $pemakaians->links() }}</div>
        @endif
    </div>
@endsection
