@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan kondisi inventory hari ini')

@section('content')

    @if ($bahanKritisList->count() > 0)
        <div class="alert-stok-kritis mb-3 d-flex align-items-start gap-2">
            <i class="bi bi-exclamation-triangle-fill text-warning mt-1"></i>
            <div>
                <strong>{{ $bahanKritisList->count() }} bahan baku</strong> berada di bawah stok minimum:
                <span class="text-danger fw-semibold">{{ $bahanKritisList->pluck('nama_bahan')->join(', ') }}</span>
            </div>
            <a href="{{ route('bahan-baku.index', ['filter' => 'kritis']) }}" class="ms-auto btn btn-sm btn-outline-danger"
                style="white-space:nowrap">Lihat</a>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#fff0e6"><i class="bi bi-box2 text-warning fs-4"></i></div>
                    <div>
                        <div class="stat-value">{{ $totalBahan }}</div>
                        <div class="stat-label">Total Bahan Baku</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#ffeaea"><i
                            class="bi bi-exclamation-triangle text-danger fs-4"></i></div>
                    <div>
                        <div class="stat-value text-danger">{{ $bahanKritis }}</div>
                        <div class="stat-label">Bahan Stok Kritis</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#e6f4ff"><i class="bi bi-cart-plus text-primary fs-4"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalPembelian }}</div>
                        <div class="stat-label">Pembelian Bulan Ini</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#e6fff4"><i class="bi bi-cart-dash text-success fs-4"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $totalPemakaian }}</div>
                        <div class="stat-label">Pemakaian Bulan Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title">Aktivitas Stok 6 Bulan Terakhir</span>
                </div>
                <div class="card-body">
                    <canvas id="chartAktivitas" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span class="card-title"><i class="bi bi-calculator me-1"></i>Perlu Dipesan (EOQ)</span>
                    <a href="#" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    @forelse($rekomendasiEoq as $eoq)
                        <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom">
                            <div
                                style="width:36px;height:36px;background:#fff0e6;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="bi bi-arrow-repeat text-warning"></i>
                            </div>
                            <div style="min-width:0">
                                <div class="fw-semibold small text-truncate">{{ $eoq->bahanBaku->nama_bahan }}</div>
                                <div class="text-muted" style="font-size:0.75rem">
                                    Stok: <span class="text-danger fw-semibold">{{ $eoq->bahanBaku->stok_saat_ini }}</span>
                                    | ROP: {{ $eoq->reorder_point }} {{ $eoq->bahanBaku->satuan }}
                                </div>
                            </div>
                            <div class="ms-auto text-end" style="white-space:nowrap">
                                <div class="small fw-bold text-primary">Q*: {{ $eoq->eoq_result }}</div>
                                <div class="text-muted" style="font-size:0.72rem">{{ $eoq->bahanBaku->satuan }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 small">
                            <i class="bi bi-check-circle fs-3 d-block mb-1 text-success"></i>
                            Semua stok aman, tidak ada yang perlu dipesan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @if (auth()->user()->isAdmin() || auth()->user()->isPemilik())
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="card-title">Pembelian Terbaru</span>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Pemasok</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembelianTerbaru as $p)
                                    <tr>
                                        <td><a href="{{ route('pembelian.show', $p) }}"
                                                class="text-decoration-none fw-semibold">{{ $p->nomor_transaksi }}</a>
                                            <div class="text-muted" style="font-size:0.75rem">
                                                {{ $p->tanggal_pembelian->format('d M Y') }}</div>
                                        </td>
                                        <td class="small">{{ $p->pemasok->nama_pemasok }}</td>
                                        <td class="small fw-semibold">Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3 small">Belum ada transaksi
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->isAdmin())
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <span class="card-title">Permintaan Bahan Pending</span>
                        <a href="#" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>No. Permintaan</th>
                                    <th>Pengaju</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permintaanPending as $pm)
                                    <tr>
                                        <td>
                                            <a href="{{ route('permintaan-bahan.show', $pm) }}"
                                                class="text-decoration-none fw-semibold">{{ $pm->nomor_permintaan }}</a>
                                            <div class="text-muted" style="font-size:0.75rem">
                                                {{ $pm->tanggal_permintaan->format('d M Y') }}</div>
                                        </td>
                                        <td class="small">{{ $pm->pengaju->name }}</td>
                                        <td>
                                            <a href="{{ route('permintaan-bahan.show', $pm) }}"
                                                class="btn btn-sm btn-outline-primary">Proses</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3 small">Tidak ada permintaan
                                            pending</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if ($bahanKritisList->count() > 0 && auth()->user()->isPemilik())
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><span class="card-title text-danger"><i
                                class="bi bi-exclamation-triangle me-1"></i>Bahan Baku Stok Kritis</span></div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Bahan Baku</th>
                                    <th>Kategori</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Minimum</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bahanKritisList as $b)
                                    <tr>
                                        <td class="fw-semibold">{{ $b->nama_bahan }}</td>
                                        <td><span class="badge bg-light text-dark">{{ $b->kategori }}</span></td>
                                        <td class="stok-kritis fw-bold">{{ $b->stok_saat_ini }} {{ $b->satuan }}</td>
                                        <td class="text-muted">{{ $b->stok_minimum }} {{ $b->satuan }}</td>
                                        <td><span class="badge bg-danger">Kritis</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const ctx = document.getElementById('chartAktivitas').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($grafikData['labels']),
                datasets: [{
                        label: 'Pembelian',
                        data: @json($grafikData['pembelian']),
                        backgroundColor: 'rgba(230,92,30,0.75)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Pemakaian',
                        data: @json($grafikData['pemakaian']),
                        backgroundColor: 'rgba(25,135,84,0.65)',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#f0f2f5'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
