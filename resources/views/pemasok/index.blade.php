@extends('layouts.app')
@section('title', 'Pemasok')
@section('page-title', 'Pemasok')
@section('page-subtitle', 'Data Master → Pemasok')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <span class="card-title me-auto">Daftar Pemasok</span>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('pemasok.create') }}" class="btn btn-sm btn-primary text-white">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Pemasok
                </a>
            @endif
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari nama pemasok...">
                </div>
                <div class="col-sm-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif
                        </option>
                    </select>
                </div>
                <div class="col-sm-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Cari</button>
                    <a href="{{ route('pemasok.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama Pemasok</th>
                        <th>Kontak Person</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pemasoks as $p)
                        <tr>
                            <td class="fw-semibold">{{ $p->nama_pemasok }}</td>
                            <td class="small text-muted">{{ $p->kontak_person ?? '-' }}</td>
                            <td class="small">{{ $p->telepon ?? '-' }}</td>
                            <td class="small">{{ $p->email ?? '-' }}</td>
                            <td>
                                @if ($p->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('pemasok.show', $p) }}" class="btn btn-sm btn-outline-secondary"
                                        title="Detail"><i class="bi bi-eye"></i></a>
                                    @if (auth()->user()->isAdmin())
                                        <a href="{{ route('pemasok.edit', $p) }}" class="btn btn-sm btn-outline-primary"
                                            title="Edit"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('pemasok.destroy', $p) }}" method="POST"
                                            onsubmit="return confirm('Hapus pemasok ini?')">
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
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data pemasok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($pemasoks->hasPages())
            <div class="card-body pt-2">{{ $pemasoks->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
