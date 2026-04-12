@extends('layouts.app')
@section('title', $koreksiStok->nomor_transaksi)
@section('page-title', 'Detail Koreksi Stok')
@section('page-subtitle', 'Transaksi → Koreksi Stok')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">{{ $koreksiStok->nomor_transaksi }}</span>
                    <a href="{{ route('koreksi-stok.index') }}" class="btn btn-sm btn-outline-secondary"><i
                            class="bi bi-arrow-left me-1"></i>Kembali</a>
                </div>
                <div class="card-body">
                    <dl class="row" style="font-size:0.9rem">
                        <dt class="col-sm-4 text-muted">Tanggal Koreksi</dt>
                        <dd class="col-sm-8">{{ $koreksiStok->tanggal_koreksi->format('d M Y') }}</dd>

                        <dt class="col-sm-4 text-muted">Bahan Baku</dt>
                        <dd class="col-sm-8 fw-semibold">{{ $koreksiStok->bahanBaku->nama_bahan }}
                            <span class="text-muted fw-normal">({{ $koreksiStok->bahanBaku->kode_bahan }})</span>
                        </dd>

                        <dt class="col-sm-4 text-muted">Stok Sebelum</dt>
                        <dd class="col-sm-8">{{ number_format($koreksiStok->jumlah_sebelum, 2, ',', '.') }}
                            {{ $koreksiStok->bahanBaku->satuan }}</dd>

                        <dt class="col-sm-4 text-muted">Stok Sesudah</dt>
                        <dd class="col-sm-8">{{ number_format($koreksiStok->jumlah_sesudah, 2, ',', '.') }}
                            {{ $koreksiStok->bahanBaku->satuan }}</dd>

                        <dt class="col-sm-4 text-muted">Selisih</dt>
                        <dd class="col-sm-8 fw-bold {{ $koreksiStok->selisih >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $koreksiStok->selisih >= 0 ? '+' : '' }}{{ number_format($koreksiStok->selisih, 2, ',', '.') }}
                            {{ $koreksiStok->bahanBaku->satuan }}
                        </dd>

                        <dt class="col-sm-4 text-muted">Alasan</dt>
                        <dd class="col-sm-8">{{ $koreksiStok->alasan }}</dd>

                        <dt class="col-sm-4 text-muted">Diinput Oleh</dt>
                        <dd class="col-sm-8">{{ $koreksiStok->user->name }}</dd>

                        <dt class="col-sm-4 text-muted">Waktu Input</dt>
                        <dd class="col-sm-8 text-muted small">{{ $koreksiStok->created_at->format('d M Y, H:i') }} WIB</dd>
                    </dl>
                    <div class="alert alert-light border small mt-2 mb-0">
                        <i class="bi bi-lock me-1 text-muted"></i>
                        Data koreksi stok bersifat permanen dan tidak dapat diubah atau dihapus (audit trail).
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
