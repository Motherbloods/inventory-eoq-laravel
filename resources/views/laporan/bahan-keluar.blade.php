@extends('layouts.app')
@section('title', 'Laporan Bahan Keluar')
@section('page-title', 'Laporan Bahan Keluar')
@section('page-subtitle', 'Riwayat pemakaian bahan baku berdasarkan periode')

@section('content')
    <div class="card">
        <div class="card-header d-flex flex-wrap align-items-center gap-2">
            <span class="card-title me-auto">Bahan Keluar (Pemakaian)</span>
            <a href="{{ route('laporan.export', ['type' => 'bahan-keluar', 'format' => 'pdf', 'dari' => $filter['dari'], 'sampai' => $filter['sampai']]) }}"
                class="btn btn-sm btn-outline-danger"><i class="bi bi-file-pdf me-1"></i>PDF</a>
            <a href="{{ route('laporan.export', ['type' => 'bahan-keluar', 'format' => 'excel', 'dari' => $filter['dari'], 'sampai' => $filter['sampai']]) }}"
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
                        <th>Kategori</th>
                        <th class="text-end">Jumlah Keluar</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $i => $d)
                        <tr>
                            <td class="text-muted small">{{ $i + 1 }}</td>
                            <td class="small text-muted">{{ $d->pemakaian->tanggal_pemakaian->format('d M Y') }}</td>
                            <td class="small fw-semibold">{{ $d->pemakaian->nomor_transaksi }}</td>
                            <td class="small fw-semibold">{{ $d->bahanBaku->nama_bahan }}</td>
                            <td><span class="badge bg-light text-dark border"
                                    style="font-size:0.7rem">{{ $d->bahanBaku->kategori }}</span></td>
                            <td class="text-end small fw-semibold text-danger">-{{ number_format($d->jumlah, 2, ',', '.') }}
                                {{ $d->bahanBaku->satuan }}</td>
                            <td class="small text-muted">{{ $d->pemakaian->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Tidak ada data pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($details->count() > 0)
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="5" class="text-end fw-bold">Total Keluar</td>
                            <td class="text-end fw-bold text-danger">{{ number_format($details->sum('jumlah'), 2, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
