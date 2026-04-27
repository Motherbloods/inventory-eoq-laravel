@extends('layouts.app')
@section('title', 'Koreksi Stok')
@section('page-title', 'Koreksi Stok')
@section('page-subtitle', 'Transaksi → Koreksi Stok')

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <span class="card-title me-auto">Riwayat Koreksi Stok</span>
            <a href="{{ route('koreksi-stok.create') }}" class="btn btn-sm btn-primary text-white"><i
                    class="bi bi-plus-lg me-1"></i>Tambah Koreksi</a>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Bahan Baku</th>
                        <th class="text-end">Sebelum</th>
                        <th class="text-end">Sesudah</th>
                        <th class="text-end">Selisih</th>
                        <th>Admin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($koreksis as $k)
                        <tr>
                            <td class="small fw-semibold">{{ $k->nomor_transaksi }}</td>
                            <td class="small text-muted">{{ $k->tanggal_koreksi->format('d M Y') }}</td>
                            <td class="small fw-semibold">{{ $k->bahanBaku->nama_bahan }}</td>
                            <td class="text-end small">{{ number_format($k->jumlah_sebelum, 2, ',', '.') }}</td>
                            <td class="text-end small">{{ number_format($k->jumlah_sesudah, 2, ',', '.') }}</td>
                            <td class="text-end small fw-bold {{ $k->selisih >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $k->selisih >= 0 ? '+' : '' }}{{ number_format($k->selisih, 2, ',', '.') }}
                            </td>
                            <td class="small text-muted">{{ $k->user->name }}</td>
                            <td><a href="{{ route('koreksi-stok.show', $k) }}" class="btn btn-sm btn-outline-secondary"><i
                                        class="bi bi-eye"></i></a></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada koreksi stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($koreksis->hasPages())
            <div class="card-body pt-2">{{ $koreksis->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
@endsection
