<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Inventory Toko Roti Andika</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>

<body>

    <div class="sidebar-overlay d-none position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"
        style="z-index:999" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <nav id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="brand-text">Toko Roti Andika</div>
                <div class="brand-sub">Sistem Inventory</div>
            </div>
        </div>

        <div class="sidebar-nav">

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            @if (auth()->user()->isAdmin() || auth()->user()->isPemilik())
                <div class="nav-section-label">Data Master</div>
                <a href="{{ route('bahan-baku.index') }}"
                    class="nav-link {{ request()->routeIs('bahan-baku.*') ? 'active' : '' }}"> <i
                        class="bi bi-box2"></i> Bahan Baku
                </a>
                <a href="{{ route('pemasok.index') }}"
                    class="nav-link {{ request()->routeIs('pemasok.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i> Pemasok
                </a>
            @endif

            @if (auth()->user()->isAdmin())
                <div class="nav-section-label">Transaksi</div>
                <a href="{{ route('pembelian.index') }}"
                    class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-plus"></i> Pembelian (Masuk)
                </a>
                <a href="{{ route('pemakaian.index') }}"
                    class="nav-link {{ request()->routeIs('pemakaian.*') ? 'active' : '' }}">
                    <i class="bi bi-cart-dash"></i> Pemakaian (Keluar)
                </a>
                <a href="{{ route('koreksi-stok.index') }}"
                    class="nav-link {{ request()->routeIs('koreksi-stok.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Koreksi Stok
                </a>
            @endif

            @if (auth()->user()->isAdmin() || auth()->user()->isProduksi())
                <div class="nav-section-label">Stok & Permintaan</div>
                <a href="{{ route('stok-bahan') }}"
                    class="nav-link {{ request()->routeIs('stok-bahan') ? 'active' : '' }}">
                    <i class="bi bi-box2"></i> Ketersediaan Bahan
                </a>
                <a href="{{ route('permintaan-bahan.index') }}"
                    class="nav-link {{ request()->routeIs('permintaan-bahan.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check"></i> Permintaan Bahan
                    @php $pending = \App\Models\PermintaanBahan::where('status','pending')->count(); @endphp
                    @if ($pending > 0 && auth()->user()->isAdmin())
                        <span class="badge-count">{{ $pending }}</span>
                    @endif
                </a>
            @endif

            @if (auth()->user()->isAdmin() || auth()->user()->isPemilik())
                <div class="nav-section-label">EOQ</div>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('eoq.setting') }}"
                        class="nav-link {{ request()->routeIs('eoq.setting') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i> Parameter EOQ
                    </a>
                @endif
                <a href="{{ route('eoq.hasil') }}"
                    class="nav-link {{ request()->routeIs('eoq.hasil') ? 'active' : '' }}">
                    <i class="bi bi-calculator"></i> Hasil EOQ & ROP
                </a>

                <div class="nav-section-label">Laporan</div>
                <a href="#" class="nav-link {{ request()->routeIs('laporan.stok-akhir') ? 'active' : '' }}">
                    <i class="bi bi-archive"></i> Stok Akhir
                </a>
                <a href="#" class="nav-link {{ request()->routeIs('laporan.bahan-masuk') ? 'active' : '' }}">
                    <i class="bi bi-arrow-down-circle"></i> Bahan Masuk
                </a>
                <a href="#" class="nav-link {{ request()->routeIs('laporan.bahan-keluar') ? 'active' : '' }}">
                    <i class="bi bi-arrow-up-circle"></i> Bahan Keluar
                </a>
                <a href="#" class="nav-link {{ request()->routeIs('laporan.reorder') ? 'active' : '' }}">
                    <i class="bi bi-exclamation-triangle"></i> Perlu Dipesan
                </a>
            @endif

            @if (auth()->user()->isAdmin())
                <div class="nav-section-label">Administrasi</div>
                <a href="{{ route('users.index') }}"
                    class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"> <i class="bi bi-people"></i>
                    Manajemen Pengguna
                </a>
            @endif

        </div>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div style="min-width:0">
                    <div style="color:#fff;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ auth()->user()->name }}</div>
                    <div style="color:rgba(255,255,255,0.4);font-size:0.7rem">{{ auth()->user()->email }}</div>
                </div>
                <span class="role-badge bg-warning text-dark">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
            <div class="d-flex gap-2 mt-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-sm w-50"
                    style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);border:none;font-size:0.75rem">
                    <i class="bi bi-person-gear"></i> Profil
                </a>
                <form action="{{ route('logout') }}" method="POST" class="w-50">
                    @csrf
                    <button class="btn btn-sm w-100"
                        style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.7);border:none;font-size:0.75rem">
                        <i class="bi bi-box-arrow-right"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div id="main-wrapper">

        <div id="topbar">
            <button class="d-lg-none btn btn-sm me-1" style="background:none;border:none;padding:4px"
                onclick="toggleSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-sub">@yield('page-subtitle', 'Toko Roti Andika')</div>
            </div>
            <div class="topbar-actions">
                @php $kritisCnt = \App\Models\BahanBaku::whereColumn('stok_saat_ini','<=','stok_minimum')->count(); @endphp
                @if ($kritisCnt > 0)
                    <a href="{{ route('bahan-baku.index', ['filter' => 'kritis']) }}"
                        class="notification-btn text-decoration-none"
                        title="{{ $kritisCnt }} bahan di bawah stok minimum">
                        <i class="bi bi-bell"></i>
                        <span class="notif-dot"></span>
                    </a>
                @endif
                <a href="#" class="text-decoration-none" style="color:inherit">
                    <div class="d-flex align-items-center gap-2 px-2 py-1 rounded" style="background:#f4f6f9">
                        <div
                            style="width:28px;height:28px;background:#e65c1e;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.75rem;font-weight:600">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span style="font-size:0.8rem;font-weight:500;color:#1e2a3a">{{ auth()->user()->name }}</span>
                    </div>
                </a>
            </div>
        </div>

        <div id="page-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible d-flex align-items-center gap-2 mb-3" role="alert"
                    style="border-radius:10px;border:none;border-left:4px solid #198754">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible d-flex align-items-center gap-2 mb-3" role="alert"
                    style="border-radius:10px;border:none;border-left:4px solid #dc3545">
                    <i class="bi bi-exclamation-circle-fill text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible mb-3"
                    style="border-radius:10px;border:none;border-left:4px solid #dc3545">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                        <strong>Terdapat kesalahan pada input:</strong>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                    <ul class="mb-0 ps-3" style="font-size:0.83rem">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('d-none');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.add('d-none');
        }
        // Auto-dismiss alerts after 5s
        setTimeout(() => {
            document.querySelectorAll('.alert.alert-success').forEach(el => {
                bootstrap.Alert.getOrCreateInstance(el).close();
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>
