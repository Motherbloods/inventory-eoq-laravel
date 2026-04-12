@extends('layouts.app')
@section('title', $pemakaian->nomor_transaksi)
@section('page-title', 'Detail Pemakaian')
@section('page-subtitle', 'Transaksi → Pemakaian')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">{{ $pemakaian->nomor_transaksi }}</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('pemakaian.edit', $pemakaian) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-pencil me-1"></i>Edit</a>
                        <a href="{{ route('pemakaian.index') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-arrow-left me-1"></i>Kembali</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="text-muted small">Tanggal</div>
                            <div class="fw-semibold">{{ $pemakaian->tanggal_pemakaian->format('d M Y') }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Diinput Oleh</div>
                            <div class="fw-semibold">{{ $pemakaian->user->name }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Keterangan</div>
                            <div>{{ $pemakaian->keterangan ?? '-' }}</div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bahan Baku</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemakaian->detail as $i => $d)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $d->bahanBaku->nama_bahan }}</div>
                                        <div class="text-muted small">{{ $d->bahanBaku->kode_bahan }}</div>
                                    </td>
                                    <td class="text-end fw-semibold text-danger">
                                        -{{ number_format($d->jumlah, 2, ',', '.') }} {{ $d->bahanBaku->satuan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
