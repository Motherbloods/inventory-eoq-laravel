<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\EoqSetting;
use App\Models\PembelianBahanBaku;
use App\Models\PemakaianBahanBaku;
use App\Models\PermintaanBahan;
use Illuminate\Http\Request;

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
        ));
    }

    public function chartData(Request $request)
    {
        $jumlahBulan = max(1, min(24, (int) $request->get('bulan', 6)));
        $offset = (int) $request->get('offset', 0);

        if ($offset > 0)
            $offset = 0;

        $bulanList = collect(range($jumlahBulan - 1, 0))->map(function ($i) use ($offset) {
            return now()->addMonths($offset)->subMonths($i);
        });

        $labels = $bulanList->map(fn($b) => $b->translatedFormat('M Y'))->toArray();
        $pembelian = $bulanList->map(
            fn($b) =>
            PembelianBahanBaku::whereYear('tanggal_pembelian', $b->year)
                ->whereMonth('tanggal_pembelian', $b->month)
                ->count()
        )->toArray();
        $pemakaian = $bulanList->map(
            fn($b) =>
            PemakaianBahanBaku::whereYear('tanggal_pemakaian', $b->year)
                ->whereMonth('tanggal_pemakaian', $b->month)
                ->count()
        )->toArray();

        $awal = $bulanList->first()->translatedFormat('M Y');
        $akhir = $bulanList->last()->translatedFormat('M Y');
        $periode = ($awal === $akhir) ? $awal : "$awal – $akhir";

        return response()->json([
            'labels' => $labels,
            'pembelian' => $pembelian,
            'pemakaian' => $pemakaian,
            'periode' => $periode,
            'offset' => $offset,
            'bulan' => $jumlahBulan,
        ]);
    }
}