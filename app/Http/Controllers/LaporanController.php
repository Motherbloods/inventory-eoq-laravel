<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\EoqSetting;
use App\Models\PembelianDetail;
use App\Models\PemakaianDetail;
use Illuminate\Http\Request;

class LaporanController extends Controller
{

    private function baseFilter(Request $request): array
    {
        return [
            'dari' => $request->dari ?? now()->startOfMonth()->toDateString(),
            'sampai' => $request->sampai ?? now()->toDateString(),
            'bahan_id' => $request->bahan_id,
            'kategori' => $request->kategori,
            'pemasok_id' => $request->pemasok_id,
        ];
    }


    public function stokAkhir(Request $request)
    {
        $query = BahanBaku::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_bahan', 'like', '%' . $request->search . '%');
        }

        $bahanBakus = $query->orderBy('nama_bahan')->get();
        $kategoris = BahanBaku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('laporan.stok-akhir', compact('bahanBakus', 'kategoris'));
    }


    public function bahanMasuk(Request $request)
    {
        $filter = $this->baseFilter($request);

        $query = PembelianDetail::with('bahanBaku', 'pembelian.pemasok')
            ->whereBetween(
                \DB::raw('DATE(pembelian_bahan_bakus.tanggal_pembelian)'),
                [$filter['dari'], $filter['sampai']]
            )
            ->join('pembelian_bahan_bakus', 'pembelian_details.pembelian_id', '=', 'pembelian_bahan_bakus.id');

        if (!empty($filter['bahan_id'])) {
            $query->where('pembelian_details.bahan_baku_id', $filter['bahan_id']);
        }

        if (!empty($filter['pemasok_id'])) {
            $query->where('pembelian_bahan_bakus.pemasok_id', $filter['pemasok_id']);
        }

        if (!empty($filter['kategori'])) {
            $query->whereHas('bahanBaku', fn($q) => $q->where('kategori', $filter['kategori']));
        }

        $details = $query->orderBy('pembelian_bahan_bakus.tanggal_pembelian', 'desc')->get();
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        $kategoris = BahanBaku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $pemasoks = \App\Models\Pemasok::where('is_active', true)->orderBy('nama_pemasok')->get();

        return view('laporan.bahan-masuk', compact('details', 'bahanBakus', 'kategoris', 'pemasoks', 'filter'));
    }


    public function bahanKeluar(Request $request)
    {
        $filter = $this->baseFilter($request);

        $query = PemakaianDetail::with('bahanBaku', 'pemakaian.user')
            ->whereBetween(
                \DB::raw('DATE(pemakaian_bahan_bakus.tanggal_pemakaian)'),
                [$filter['dari'], $filter['sampai']]
            )
            ->join('pemakaian_bahan_bakus', 'pemakaian_details.pemakaian_id', '=', 'pemakaian_bahan_bakus.id');

        if (!empty($filter['bahan_id'])) {
            $query->where('pemakaian_details.bahan_baku_id', $filter['bahan_id']);
        }

        if (!empty($filter['kategori'])) {
            $query->whereHas('bahanBaku', fn($q) => $q->where('kategori', $filter['kategori']));
        }

        $details = $query->orderBy('pemakaian_bahan_bakus.tanggal_pemakaian', 'desc')->get();
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        $kategoris = BahanBaku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('laporan.bahan-keluar', compact('details', 'bahanBakus', 'kategoris', 'filter'));
    }


    public function reorder(Request $request)
    {
        $bahanMinimum = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini')
            ->get();

        $bahanReorderEoq = EoqSetting::with('bahanBaku')
            ->whereNotNull('reorder_point')
            ->whereHas('bahanBaku', function ($q) {
                $q->whereColumn('stok_saat_ini', '<=', 'eoq_settings.reorder_point');
            })
            ->get();

        return view('laporan.reorder', compact('bahanMinimum', 'bahanReorderEoq'));
    }


    public function export(Request $request, string $type)
    {
        $format = $request->query('format');

        abort_unless(in_array($type, ['stok-akhir', 'bahan-masuk', 'bahan-keluar', 'reorder']), 404);
        abort_unless(in_array($format, ['pdf', 'excel']), 404);

        $data = match ($type) {
            'stok-akhir' => ['bahanBakus' => BahanBaku::orderBy('nama_bahan')->get()],
            'bahan-masuk' => $this->dataBahanMasuk($request),
            'bahan-keluar' => $this->dataBahanKeluar($request),
            'reorder' => [
                'bahanMinimum' => BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->get(),
                'bahanReorderEoq' => EoqSetting::with('bahanBaku')->whereNotNull('reorder_point')
                    ->whereHas('bahanBaku', fn($q) => $q->whereColumn('stok_saat_ini', '<=', 'eoq_settings.reorder_point'))
                    ->get(),
            ],
        };

        $view = "laporan.export.{$type}";
        $title = "Laporan " . ucwords(str_replace('-', ' ', $type));

        if ($format === 'pdf') {
            // Return view untuk window.print()
            return view($view, $data);
        }

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\LaporanExport($view, $data, $title),
            "{$type}-" . now()->format('Ymd') . ".xlsx"
        );
    }


    private function dataBahanMasuk(Request $request): array
    {
        $filter = $this->baseFilter($request);
        $query = PembelianDetail::with('bahanBaku', 'pembelian.pemasok')
            ->join('pembelian_bahan_bakus', 'pembelian_details.pembelian_id', '=', 'pembelian_bahan_bakus.id')
            ->whereBetween(\DB::raw('DATE(pembelian_bahan_bakus.tanggal_pembelian)'), [$filter['dari'], $filter['sampai']]);

        return ['details' => $query->orderBy('pembelian_bahan_bakus.tanggal_pembelian')->get(), 'filter' => $filter];
    }

    private function dataBahanKeluar(Request $request): array
    {
        $filter = $this->baseFilter($request);
        $query = PemakaianDetail::with('bahanBaku', 'pemakaian')
            ->join('pemakaian_bahan_bakus', 'pemakaian_details.pemakaian_id', '=', 'pemakaian_bahan_bakus.id')
            ->whereBetween(\DB::raw('DATE(pemakaian_bahan_bakus.tanggal_pemakaian)'), [$filter['dari'], $filter['sampai']]);

        return ['details' => $query->orderBy('pemakaian_bahan_bakus.tanggal_pemakaian')->get(), 'filter' => $filter];
    }
}