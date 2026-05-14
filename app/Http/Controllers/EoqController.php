<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\EoqSetting;
use App\Http\Requests\StoreEoqSettingRequest;
use App\Services\EoqService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EoqController extends Controller
{
    public function __construct(private EoqService $eoqService)
    {
    }

    public function setting(Request $request)
    {
        $query = BahanBaku::with('eoqSetting');

        if ($request->filled('search')) {
            $query->where('nama_bahan', 'like', '%' . $request->search . '%');
        }

        $bahanBakus = $query->orderBy('nama_bahan')->paginate(15)->withQueryString();

        return view('eoq.setting', compact('bahanBakus'));
    }

    public function storeSetting(StoreEoqSettingRequest $request)
    {
        $setting = EoqSetting::updateOrCreate(
            ['bahan_baku_id' => $request->bahan_baku_id],
            $request->validated()
        );

        // Langsung hitung setelah simpan
        $this->eoqService->hitungDanSimpan($setting);

        return back()->with('success', 'Parameter EOQ berhasil disimpan dan dihitung.');
    }

    public function hitung(BahanBaku $bahanBaku)
    {
        $setting = $bahanBaku->eoqSetting;

        if (!$setting) {
            return back()->with('error', 'Parameter EOQ untuk bahan ini belum diisi.');
        }

        $this->eoqService->hitungDanSimpan($setting);

        return back()->with('success', "EOQ dan Reorder Point untuk {$bahanBaku->nama_bahan} berhasil dihitung ulang.");
    }

    public function hitungSemua()
    {
        $settings = EoqSetting::all();
        $count = 0;

        foreach ($settings as $setting) {
            $this->eoqService->hitungDanSimpan($setting);
            $count++;
        }

        return back()->with('success', "EOQ berhasil dihitung ulang untuk {$count} bahan baku.");
    }

    public function hasil(Request $request)
    {
        $query = EoqSetting::with('bahanBaku');

        if ($request->filled('search')) {
            $query->whereHas('bahanBaku', fn($q) => $q->where('nama_bahan', 'like', '%' . $request->search . '%'));
        }

        // Filter: hanya yang perlu dipesan ulang (stok ≤ ROP)
        if ($request->filter === 'perlu_dipesan') {
            $query->whereHas('bahanBaku', function ($q) {
                $q->whereColumn('stok_saat_ini', '<=', 'eoq_settings.reorder_point');
            });
        }

        // Filter: stok di bawah safety stock
        if ($request->filter === 'bawah_safety') {
            $query->where('safety_stock', '>', 0)
                ->whereHas('bahanBaku', function ($q) {
                    $q->whereColumn('stok_saat_ini', '<', 'eoq_settings.safety_stock');
                });
        }

        $eoqResults = $query->paginate(15)->withQueryString();

        return view('eoq.hasil', compact('eoqResults'));
    }
    public function hitungStdDev(BahanBaku $bahanBaku, Request $request)
    {
        // Ambil jumlah hari histori, default 90 hari, batasi 7–365
        $hari = (int) $request->get('hari', 90);
        $hari = max(7, min(365, $hari));

        $pemakaianHarian = DB::table('pemakaian_details as pd')
            ->join('pemakaian_bahan_bakus as pb', 'pb.id', '=', 'pd.pemakaian_id')
            ->where('pd.bahan_baku_id', $bahanBaku->id)
            ->where('pb.tanggal_pemakaian', '>=', now()->subDays($hari)->toDateString())
            ->selectRaw('DATE(pb.tanggal_pemakaian) as tgl, SUM(pd.jumlah) as total')
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->pluck('total')
            ->map(fn($v) => (float) $v)
            ->toArray();

        $n = count($pemakaianHarian);

        // Butuh minimal 2 hari data untuk menghitung standar deviasi
        if ($n < 2) {
            return response()->json([
                'std_dev' => 0,
                'n' => $n,
                'pesan' => "Hanya ditemukan {$n} hari data pemakaian dalam {$hari} hari terakhir. Minimal 2 hari diperlukan. Masukkan σ secara manual.",
            ]);
        }

        // Hitung rata-rata harian
        $mean = array_sum($pemakaianHarian) / $n;

        // Sample standard deviation (pakai n-1 / Bessel's correction)
        $variance = array_sum(
            array_map(fn($x) => ($x - $mean) ** 2, $pemakaianHarian)
        ) / ($n - 1);

        $stdDev = round(sqrt($variance), 4);

        return response()->json([
            'std_dev' => $stdDev,
            'n' => $n,
            'mean' => round($mean, 4),
            'hari' => $hari,
            'pesan' => "Dihitung dari {$n} hari pemakaian dalam {$hari} hari terakhir (rata-rata " . round($mean, 2) . " / hari).",
        ]);
    }
}