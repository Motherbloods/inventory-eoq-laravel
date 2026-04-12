@extends('layouts.app')
@section('title', $pembelian->nomor_transaksi)
@section('page-title', 'Detail Pembelian')
@section('page-subtitle', 'Transaksi → Pembelian')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">{{ $pembelian->nomor_transaksi }}</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pembelian.edit', $pembelian) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-pencil me-1"></i>Edit</a>
                        <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-arrow-left me-1"></i>Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6 col-md-3">
                            <div class="text-muted small">Tanggal</div>
                            <div class="fw-semibold">{{ $pembelian->tanggal_pembelian->format('d M Y') }}</div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="text-muted small">Pemasok</div>
                            <div class="fw-semibold">{{ $pembelian->pemasok->nama_pemasok }}</div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="text-muted small">Diinput Oleh</div>
                            <div class="fw-semibold">{{ $pembelian->user->name }}</div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="text-muted small">Total Pembelian</div>
                            <div class="fw-bold text-primary fs-5">Rp
                                {{ number_format($pembelian->total_harga, 0, ',', '.') }}</div>
                        </div>
                        @if ($pembelian->keterangan)
                            <div class="col-12">
                                <div class="text-muted small">Keterangan</div>
                                <div>{{ $pembelian->keterangan }}</div>
                            </div>
                        @endif
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bahan Baku</th>
                                <th class="text-end">Jumlah</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pembelian->detail as $i => $d)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $d->bahanBaku->nama_bahan }}</div>
                                        <div class="text-muted small">{{ $d->bahanBaku->kode_bahan }}</div>
                                    </td>
                                    <td class="text-end">{{ number_format($d->jumlah, 2, ',', '.') }}
                                        {{ $d->bahanBaku->satuan }}</td>
                                    <td class="text-end">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-end fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total</td>
                                <td class="text-end fw-bold text-primary">Rp
                                    {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
