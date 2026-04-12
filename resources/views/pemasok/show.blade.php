@extends('layouts.app')
@section('title', $pemasok->nama_pemasok)
@section('page-title', $pemasok->nama_pemasok)
@section('page-subtitle', 'Data Master → Pemasok')
@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">Informasi Pemasok</span>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('pemasok.edit', $pemasok) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-pencil"></i></a>
                    @endif
                </div>
                <div class="card-body">
                    <dl class="row mb-0" style="font-size:0.875rem">
                        <dt class="col-5 text-muted">Status</dt>
                        <dd class="col-7">
                            <span class="badge {{ $pemasok->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $pemasok->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </dd>
                        <dt class="col-5 text-muted">Kontak</dt>
                        <dd class="col-7">{{ $pemasok->kontak_person ?? '-' }}</dd>
                        <dt class="col-5 text-muted">Telepon</dt>
                        <dd class="col-7">{{ $pemasok->telepon ?? '-' }}</dd>
                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7" style="word-break:break-all">{{ $pemasok->email ?? '-' }}</dd>
                        <dt class="col-5 text-muted">Alamat</dt>
                        <dd class="col-7">{{ $pemasok->alamat ?? '-' }}</dd>
                        <dt class="col-5 text-muted">Total Order</dt>
                        <dd class="col-7 fw-bold">{{ $pemasok->pembelian_count }} transaksi</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><span class="card-title">Riwayat Pembelian Terbaru</span></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No. Transaksi</th>
                                <th>Tanggal</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembelianTerbaru as $pb)
                                <tr>
                                    <td><a href="{{ route('pembelian.show', $pb) }}"
                                            class="text-decoration-none fw-semibold small">{{ $pb->nomor_transaksi }}</a>
                                    </td>
                                    <td class="small text-muted">{{ $pb->tanggal_pembelian->format('d M Y') }}</td>
                                    <td class="text-end small fw-semibold">Rp
                                        {{ number_format($pb->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3 small">Belum ada riwayat
                                        pembelian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
