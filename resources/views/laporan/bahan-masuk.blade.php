@extends('layouts.app')
@section('title', 'Laporan Bahan Masuk')
@section('page-title', 'Laporan Bahan Masuk')
@section('page-subtitle', 'Riwayat pembelian bahan baku berdasarkan periode')

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="card-title me-auto">Bahan Masuk (Pembelian)</span>
            <a href="{{ route('laporan.export', ['type' => 'bahan-masuk', 'format' => 'pdf', 'dari' => $filter['dari'], 'sampai' => $filter['sampai']]) }}"
                class="btn btn-sm btn-outline-danger"><i class="bi bi-file-pdf me-1"></i>PDF</a>
            <a href="{{ route('laporan.export', ['type' => 'bahan-masuk', 'format' => 'excel', 'dari' => $filter['dari'], 'sampai' => $filter['sampai']]) }}"
                class="btn btn-sm btn-outline-success"><i class="bi bi-file-excel me-1"></i>Excel</a>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-2"><input type="date" name="dari" value="{{ $filter['dari'] }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-2"><input type="date" name="sampai" value="{{ $filter['sampai'] }}"
                        class="form-control form-control-sm"></div>
                <div class="col-sm-3">
                    <select name="bahan_id" class="form-select form-select-sm">
                        <option value="">Semua Bahan</option>
                        @foreach ($bahanBakus as $b)
                            <option value="{{ $b->id }}" {{ $filter['bahan_id'] == $b->id ? 'selected' : '' }}>
                                {{ $b->nama_bahan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="kategori" class="form-select form-select-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k }}" {{ $filter['kategori'] == $k ? 'selected' : '' }}>
                                {{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select name="pemasok_id" class="form-select form-select-sm">
                        <option value="">Semua Pemasok</option>
                        @foreach ($pemasoks as $p)
                            <option value="{{ $p->id }}" {{ $filter['pemasok_id'] == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_pemasok }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1 d-flex gap-1">
                    <button class="btn btn-sm btn-primary text-white flex-fill">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>No. Transaksi</th>
                        <th>Bahan Baku</th>
                        <th>Pemasok</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $i => $d)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td class="small text-muted">{{ $d->pembelian->tanggal_pembelian->format('d M Y') }}</td>
                            <td class="small fw-semibold">{{ $d->pembelian->nomor_transaksi }}</td>
                            <td class="small fw-semibold">{{ $d->bahanBaku->nama_bahan }}</td>
                            <td class="small">{{ $d->pembelian->pemasok->nama_pemasok }}</td>
                            <td class="text-end small fw-semibold text-success">+{{ number_format($d->jumlah, 2, ',', '.') }}
                                {{ $d->bahanBaku->satuan }}</td>
                            <td class="text-end small">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td class="text-end small fw-semibold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($details->count() > 0)
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="5" class="text-end fw-bold">Total</td>
                            <td class="text-end fw-bold text-success">
                                {{ number_format($details->sum('jumlah'), 2, ',', '.') }}</td>
                            <td></td>
                            <td class="text-end fw-bold text-primary">Rp
                                {{ number_format($details->sum('subtotal'), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
