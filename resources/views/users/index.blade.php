@extends('layouts.app')
@section('title', 'Manajemen Pengguna')
@section('page-title', 'Manajemen Pengguna')
@section('page-subtitle', 'Administrasi → Pengguna')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <span class="card-title me-auto">Daftar Pengguna</span>
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary text-white">
                <i class="bi bi-person-plus me-1"></i>Tambah Pengguna
            </a>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari nama atau email...">
                </div>
                <div class="col-sm-3">
                    <select name="role" class="form-select form-select-sm">
                        <option value="">Semua Role</option>
                        <option value="pemilik" {{ request('role') == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="produksi" {{ request('role') == 'produksi' ? 'selected' : '' }}>Produksi</option>
                    </select>
                </div>
                <div class="col-sm-2 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Cari</button>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div
                                        style="width:32px;height:32px;border-radius:50%;overflow:hidden;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                        <img src="{{ asset('user.png') }}" alt="User"
                                            style="width:100%;height:100%;object-fit:cover">
                                    </div>
                                    <span class="fw-semibold small">{{ $user->name }}</span>
                                    @if ($user->id === auth()->id())
                                        <span class="badge bg-light text-muted border" style="font-size:0.65rem">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="small text-muted">{{ $user->email }}</td>
                            <td>
                                <span
                                    class="badge {{ $user->role === 'pemilik' ? 'bg-warning text-dark' : ($user->role === 'admin' ? 'bg-primary' : 'bg-info text-dark') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i
                                            class="bi bi-pencil"></i></a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Hapus pengguna ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Tidak ada pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-body pt-2">{{ $users->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
