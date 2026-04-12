@extends('layouts.app')
@section('title', 'Permintaan Bahan')
@section('page-title', 'Permintaan Bahan')
@section('page-subtitle', auth()->user()->isProduksi() ? 'Permintaan saya' : 'Semua permintaan bahan produksi')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <span class="card-title me-auto">Daftar Permintaan</span>
            @if (auth()->user()->isProduksi())
                <a href="{{ route('permintaan-bahan.create') }}" class="btn btn-sm btn-primary text-white">
                    <i class="bi bi-plus-lg me-1"></i>Ajukan Permintaan
                </a>
            @endif
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="col-sm-3"><input type="date" name="dari" value="{{ request('dari') }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-3"><input type="date" name="sampai" value="{{ request('sampai') }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Cari</button>
                    <a href="{{ route('permintaan-bahan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No. Permintaan</th>
                        <th>Tanggal</th>
                        @if (auth()->user()->isAdmin())
                            <th>Pengaju</th>
                        @endif
                        <th>Status</th>
                        <th>Diproses Oleh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permintaans as $pm)
                        <tr>
                            <td class="fw-semibold small">{{ $pm->nomor_permintaan }}</td>
                            <td class="small text-muted">{{ $pm->tanggal_permintaan->format('d M Y') }}</td>
                            @if (auth()->user()->isAdmin())
                                <td class="small">{{ $pm->pengaju->name }}</td>
                            @endif
                            <td><span class="{{ $pm->statusBadgeClass() }}">{{ ucfirst($pm->status) }}</span></td>
                            <td class="small text-muted">{{ $pm->pemroses?->name ?? '-' }}</td>
                            <td>
                                <a href="{{ route('permintaan-bahan.show', $pm) }}"
                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada permintaan bahan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($permintaans->hasPages())
            <div class="card-body pt-2">{{ $permintaans->links() }}</div>
        @endif
    </div>
@endsection
