<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\EoqSetting;
use App\Http\Requests\StoreEoqSettingRequest;
use App\Services\EoqService;
use Illuminate\Http\Request;

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

        // Filter: hanya yang perlu dipesan ulang
        if ($request->filter === 'perlu_dipesan') {
            $query->whereHas('bahanBaku', function ($q) {
                $q->whereColumn('stok_saat_ini', '<=', 'eoq_settings.reorder_point');
            });
        }

        $eoqResults = $query->paginate(15)->withQueryString();

        return view('eoq.hasil', compact('eoqResults'));
    }
}