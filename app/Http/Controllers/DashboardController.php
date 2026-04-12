<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\EoqSetting;
use App\Models\PembelianBahanBaku;
use App\Models\PemakaianBahanBaku;
use App\Models\PermintaanBahan;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBahan = BahanBaku::count();
        $bahanKritis = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();
        $totalPembelian = PembelianBahanBaku::whereMonth('tanggal_pembelian', now()->month)->count();
        $totalPemakaian = PemakaianBahanBaku::whereMonth('tanggal_pemakaian', now()->month)->count();

        $bahanKritisList = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini')
            ->get();

        $rekomendasiEoq = EoqSetting::with('bahanBaku')
            ->whereNotNull('reorder_point')
            ->whereHas('bahanBaku', function ($q) {
                $q->whereColumn('stok_saat_ini', '<=', 'eoq_settings.reorder_point');
            })
            ->get();

        $pembelianTerbaru = PembelianBahanBaku::with('pemasok')
            ->latest()
            ->limit(5)
            ->get();

        $pemakaianTerbaru = PemakaianBahanBaku::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $permintaanPending = PermintaanBahan::where('status', 'pending')
            ->with('pengaju')
            ->latest()
            ->limit(5)
            ->get();

        $grafikData = $this->getGrafikData();

        return view('dashboard.index', compact(
            'totalBahan',
            'bahanKritis',
            'totalPembelian',
            'totalPemakaian',
            'bahanKritisList',
            'rekomendasiEoq',
            'pembelianTerbaru',
            'pemakaianTerbaru',
            'permintaanPending',
            'grafikData'
        ));
    }

    private function getGrafikData(): array
    {
        $bulan = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i);
        });

        $labels = $bulan->map(fn($b) => $b->translatedFormat('M Y'))->toArray();

        $pembelian = $bulan->map(function ($b) {
            return PembelianBahanBaku::whereYear('tanggal_pembelian', $b->year)
                ->whereMonth('tanggal_pembelian', $b->month)
                ->count();
        })->toArray();

        $pemakaian = $bulan->map(function ($b) {
            return PemakaianBahanBaku::whereYear('tanggal_pemakaian', $b->year)
                ->whereMonth('tanggal_pemakaian', $b->month)
                ->count();
        })->toArray();

        return compact('labels', 'pembelian', 'pemakaian');
    }
}