@extends('layouts.app')
@section('title', 'Parameter EOQ')
@section('page-title', 'Parameter EOQ')
@section('page-subtitle', 'Pengaturan parameter perhitungan Economic Order Quantity')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <form action="{{ route('eoq.hitungSemua') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-primary">
                <i class="bi bi-calculator me-1"></i>Hitung Ulang Semua EOQ
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span class="card-title">Pengaturan Parameter per Bahan Baku</span>
            <small class="text-muted">Q* = √(2DS/H) &nbsp;|&nbsp; ROP = (D/365) × Lead Time</small>
        </div>
        <div class="card-body border-bottom pb-3">
            <form method="GET" class="row g-2">
                <div class="col-sm-5">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Cari nama bahan...">
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-sm btn-primary text-white w-100">Cari</button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="min-width:180px">Bahan Baku</th>
                        <th class="text-end" title="Permintaan Tahunan">D (unit/thn)</th>
                        <th class="text-end" title="Biaya Pemesanan">S (Rp/order)</th>
                        <th class="text-end" title="Biaya Penyimpanan">H (Rp/unit/thn)</th>
                        <th class="text-center">Lead Time</th>
                        <th class="text-end text-primary">Q* (EOQ)</th>
                        <th class="text-end text-warning">ROP</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bahanBakus as $bahan)
                        @php $eoq = $bahan->eoqSetting; @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold small">{{ $bahan->nama_bahan }}</div>
                                <div class="text-muted" style="font-size:0.72rem">{{ $bahan->kode_bahan }} &bull;
                                    {{ $bahan->satuan }}</div>
                            </td>

                            {{-- Inline edit form --}}
                            <form action="{{ route('eoq.storeSetting') }}" method="POST" id="form-eoq-{{ $bahan->id }}">
                                @csrf
                                <input type="hidden" name="bahan_baku_id" value="{{ $bahan->id }}">

                                <td class="text-end" style="padding:0.4rem 0.6rem">
                                    <input type="number" name="permintaan_tahunan" form="form-eoq-{{ $bahan->id }}"
                                        value="{{ $eoq->permintaan_tahunan ?? '' }}"
                                        class="form-control form-control-sm text-end" style="min-width:90px" min="0.01"
                                        step="0.01" required>
                                </td>
                                <td class="text-end" style="padding:0.4rem 0.6rem">
                                    <input type="number" name="biaya_pemesanan" form="form-eoq-{{ $bahan->id }}"
                                        value="{{ $eoq->biaya_pemesanan ?? '' }}"
                                        class="form-control form-control-sm text-end" style="min-width:90px" min="0.01"
                                        step="0.01" required>
                                </td>
                                <td class="text-end" style="padding:0.4rem 0.6rem">
                                    <input type="number" name="biaya_penyimpanan" form="form-eoq-{{ $bahan->id }}"
                                        value="{{ $eoq->biaya_penyimpanan ?? '' }}"
                                        class="form-control form-control-sm text-end" style="min-width:90px" min="0.01"
                                        step="0.01" required>
                                </td>
                                <td class="text-center" style="padding:0.4rem 0.6rem">
                                    <input type="number" name="lead_time_hari" form="form-eoq-{{ $bahan->id }}"
                                        value="{{ $eoq->lead_time_hari ?? 1 }}"
                                        class="form-control form-control-sm text-center"
                                        style="min-width:60px;max-width:70px;margin:auto" min="1" max="365"
                                        required>
                                </td>
                            </form>

                            <td class="text-end fw-bold text-primary small">
                                {{ $eoq?->eoq_result ? number_format($eoq->eoq_result, 2, ',', '.') . ' ' . $bahan->satuan : '-' }}
                            </td>
                            <td
                                class="text-end fw-bold small {{ $eoq && $bahan->stok_saat_ini <= $eoq->reorder_point ? 'text-danger' : 'text-warning' }}">
                                {{ $eoq?->reorder_point ? number_format($eoq->reorder_point, 2, ',', '.') . ' ' . $bahan->satuan : '-' }}
                            </td>

                            <td>
                                <button type="submit" form="form-eoq-{{ $bahan->id }}"
                                    class="btn btn-sm btn-outline-primary" title="Simpan & Hitung">
                                    <i class="bi bi-save"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Tidak ada bahan baku.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bahanBakus->hasPages())
            <div class="card-body pt-2">{{ $bahanBakus->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    <div class="alert alert-light border mt-3 small">
        <strong>Keterangan rumus:</strong>
        <ul class="mb-0 mt-1">
            <li><strong>D</strong> = Permintaan tahunan (estimasi kebutuhan per tahun dalam satuan bahan)</li>
            <li><strong>S</strong> = Biaya sekali pemesanan (ongkir, biaya admin, dll dalam Rupiah)</li>
            <li><strong>H</strong> = Biaya penyimpanan per unit per tahun (persentase harga × harga satuan)</li>
            <li><strong>Q*</strong> = √(2DS/H) → jumlah optimal yang dipesan setiap kali pesan</li>
            <li><strong>ROP</strong> = (D/365) × Lead Time → titik stok dimana pemesanan harus dilakukan</li>
        </ul>
    </div>
@endsection
