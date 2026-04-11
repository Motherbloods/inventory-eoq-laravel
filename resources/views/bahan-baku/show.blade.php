@extends('layouts.app')
@section('title', $bahanBaku->nama_bahan)
@section('page-title', $bahanBaku->nama_bahan)
@section('page-subtitle', 'Detail Bahan Baku')

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">Informasi Bahan</span>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('bahan-baku.edit', $bahanBaku) }}" class="btn btn-sm btn-outline-primary"><i
                                class="bi bi-pencil"></i></a>
                    @endif
                </div>
                <div class="card-body">
                    <dl class="row mb-0" style="font-size:0.875rem">
                        <dt class="col-5 text-muted">Kode</dt>
                        <dd class="col-7"><code>{{ $bahanBaku->kode_bahan }}</code></dd>
                        <dt class="col-5 text-muted">Kategori</dt>
                        <dd class="col-7"><span class="badge bg-light text-dark border">{{ $bahanBaku->kategori }}</span>
                        </dd>
                        <dt class="col-5 text-muted">Satuan</dt>
                        <dd class="col-7">{{ $bahanBaku->satuan }}</dd>
                        <dt class="col-5 text-muted">Harga</dt>
                        <dd class="col-7">Rp {{ number_format($bahanBaku->harga_satuan, 0, ',', '.') }}</dd>
                        <dt class="col-5 text-muted">Stok Saat Ini</dt>
                        <dd class="col-7 fw-bold {{ $bahanBaku->isBawahMinimum() ? 'text-danger' : 'text-success' }}">
                            {{ number_format($bahanBaku->stok_saat_ini, 2, ',', '.') }} {{ $bahanBaku->satuan }}
                        </dd>
                        <dt class="col-5 text-muted">Stok Minimum</dt>
                        <dd class="col-7">{{ number_format($bahanBaku->stok_minimum, 2, ',', '.') }}
                            {{ $bahanBaku->satuan }}</dd>
                        @if ($bahanBaku->deskripsi)
                            <dt class="col-5 text-muted">Deskripsi</dt>
                            <dd class="col-7">{{ $bahanBaku->deskripsi }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if ($bahanBaku->eoqSetting)
                <div class="card">
                    <div class="card-header"><span class="card-title"><i class="bi bi-calculator me-1"></i>Parameter
                            EOQ</span></div>
                    <div class="card-body">
                        <dl class="row mb-0" style="font-size:0.875rem">
                            <dt class="col-7 text-muted">Permintaan/Tahun (D)</dt>
                            <dd class="col-5">{{ $bahanBaku->eoqSetting->permintaan_tahunan }} {{ $bahanBaku->satuan }}
                            </dd>
                            <dt class="col-7 text-muted">Biaya Pesan (S)</dt>
                            <dd class="col-5">Rp {{ number_format($bahanBaku->eoqSetting->biaya_pemesanan, 0, ',', '.') }}
                            </dd>
                            <dt class="col-7 text-muted">Biaya Simpan (H)</dt>
                            <dd class="col-5">Rp
                                {{ number_format($bahanBaku->eoqSetting->biaya_penyimpanan, 0, ',', '.') }}</dd>
                            <dt class="col-7 text-muted">Lead Time</dt>
                            <dd class="col-5">{{ $bahanBaku->eoqSetting->lead_time_hari }} hari</dd>
                            <dt class="col-7 text-muted fw-bold">EOQ (Q*)</dt>
                            <dd class="col-5 fw-bold text-primary">{{ $bahanBaku->eoqSetting->eoq_result }}
                                {{ $bahanBaku->satuan }}</dd>
                            <dt class="col-7 text-muted fw-bold">Reorder Point</dt>
                            <dd class="col-5 fw-bold text-warning">{{ $bahanBaku->eoqSetting->reorder_point }}
                                {{ $bahanBaku->satuan }}</dd>
                        </dl>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><span class="card-title">Riwayat Stok</span></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Jenis</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $r)
                                <tr>
                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($r['tanggal'])->format('d M Y') }}</td>
                                    <td class="small">{{ $r['keterangan'] }}</td>
                                    <td>
                                        @if ($r['jenis'] === 'masuk')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle"><i
                                                    class="bi bi-arrow-down me-1"></i>Masuk</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i
                                                    class="bi bi-arrow-up me-1"></i>Keluar</span>
                                        @endif
                                    </td>
                                    <td
                                        class="text-end fw-semibold {{ $r['jenis'] === 'masuk' ? 'text-success' : 'text-danger' }}">
                                        {{ $r['jenis'] === 'masuk' ? '+' : '-' }}{{ number_format($r['jumlah'], 2, ',', '.') }}
                                        {{ $bahanBaku->satuan }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4 small">Belum ada riwayat
                                        transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
