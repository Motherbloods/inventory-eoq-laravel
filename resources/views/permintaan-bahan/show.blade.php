@extends('layouts.app')
@section('title', $permintaan->nomor_permintaan)
@section('page-title', 'Detail Permintaan')
@section('page-subtitle', 'Permintaan Bahan')

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-lg-9">

            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">{{ $permintaan->nomor_permintaan }}</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="{{ $permintaan->statusBadgeClass() }} fs-6">{{ ucfirst($permintaan->status) }}</span>
                        <a href="{{ route('permintaan-bahan.index') }}" class="btn btn-sm btn-outline-secondary"><i
                                class="bi bi-arrow-left"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <div class="text-muted small">Tanggal Permintaan</div>
                            <div class="fw-semibold">{{ $permintaan->tanggal_permintaan->format('d M Y') }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Diajukan Oleh</div>
                            <div class="fw-semibold">{{ $permintaan->pengaju->name }}</div>
                        </div>
                        <div class="col-sm-4">
                            <div class="text-muted small">Diproses Oleh</div>
                            <div class="fw-semibold">{{ $permintaan->pemroses?->name ?? '-' }}</div>
                        </div>
                        @if ($permintaan->catatan)
                            <div class="col-12">
                                <div class="text-muted small">Catatan</div>
                                <div>{{ $permintaan->catatan }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><span class="card-title">Daftar Bahan Diminta</span></div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bahan Baku</th>
                                <th class="text-end">Stok Tersedia</th>
                                <th class="text-end">Diminta</th>
                                @if (!$permintaan->isPending())
                                    <th class="text-end">Disetujui</th>
                                @endif
                                @if (auth()->user()->isAdmin() && $permintaan->isPending())
                                    <th class="text-end">Jml Disetujui</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permintaan->detail as $i => $d)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $d->bahanBaku->nama_bahan }}</div>
                                        <div class="text-muted small">{{ $d->bahanBaku->kode_bahan }}</div>
                                    </td>
                                    <td
                                        class="text-end small {{ $d->bahanBaku->isBawahMinimum() ? 'text-danger fw-bold' : 'text-success' }}">
                                        {{ number_format($d->bahanBaku->stok_saat_ini, 2, ',', '.') }}
                                        {{ $d->bahanBaku->satuan }}
                                    </td>
                                    <td class="text-end fw-semibold">
                                        {{ number_format($d->jumlah_diminta, 2, ',', '.') }} {{ $d->bahanBaku->satuan }}
                                    </td>
                                    @if (!$permintaan->isPending())
                                        <td
                                            class="text-end fw-bold {{ $d->jumlah_disetujui > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $d->jumlah_disetujui !== null ? number_format($d->jumlah_disetujui, 2, ',', '.') . ' ' . $d->bahanBaku->satuan : '-' }}
                                        </td>
                                    @endif
                                    @if (auth()->user()->isAdmin() && $permintaan->isPending())
                                        <td class="text-end">
                                            <input type="number" name="items[{{ $i }}][jumlah_disetujui]"
                                                form="formApprove" value="{{ $d->jumlah_diminta }}" min="0"
                                                step="0.01" class="form-control form-control-sm text-end"
                                                style="max-width:120px;margin-left:auto">
                                            <input type="hidden" name="items[{{ $i }}][detail_id]"
                                                form="formApprove" value="{{ $d->id }}">
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if (auth()->user()->isAdmin() && $permintaan->isPending())
                <form id="formApprove" action="{{ route('permintaan-bahan.approve', $permintaan) }}" method="POST">
                    @csrf @method('PUT')
                </form>
                <form id="formTolak" action="{{ route('permintaan-bahan.tolak', $permintaan) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="catatan" id="catatanTolak">
                </form>

                <div class="d-flex gap-2">
                    <button type="submit" form="formApprove" class="btn btn-success text-white">
                        <i class="bi bi-check-lg me-1"></i>Setujui Permintaan
                    </button>
                    <button type="button" class="btn btn-danger" onclick="showTolakModal()">
                        <i class="bi bi-x-lg me-1"></i>Tolak Permintaan
                    </button>
                    <a href="{{ route('permintaan-bahan.index') }}" class="btn btn-outline-secondary">Kembali</a>
                </div>

                <div class="modal fade" id="modalTolak" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Tolak Permintaan</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label small">Alasan penolakan (opsional)</label>
                                <textarea id="inputCatatanTolak" class="form-control" rows="3" placeholder="Jelaskan alasan penolakan..."></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                    data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="submitTolak()">
                                    <i class="bi bi-x-lg me-1"></i>Konfirmasi Tolak
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        <script>
            function showTolakModal() {
                new bootstrap.Modal(document.getElementById('modalTolak')).show();
            }

            function submitTolak() {
                document.getElementById('catatanTolak').value = document.getElementById('inputCatatanTolak').value;
                document.getElementById('formTolak').submit();
            }
        </script>
    @endpush
@endsection
