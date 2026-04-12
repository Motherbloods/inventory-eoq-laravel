<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PermintaanBahan;
use App\Models\PermintaanDetail;
use App\Models\PemakaianBahanBaku;
use App\Models\PemakaianDetail;
use App\Http\Requests\StorePermintaanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanBahanController extends Controller
{
    public function index(Request $request)
    {
        $query = PermintaanBahan::with('pengaju', 'pemroses');

        if (auth()->user()->isProduksi()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_permintaan', [$request->dari, $request->sampai]);
        }

        $permintaans = $query->latest('tanggal_permintaan')->paginate(15)->withQueryString();

        return view('permintaan-bahan.index', compact('permintaans'));
    }

    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        return view('permintaan-bahan.create', compact('bahanBakus'));
    }

    public function store(StorePermintaanRequest $request)
    {
        DB::transaction(function () use ($request) {
            $permintaan = PermintaanBahan::create([
                'nomor_permintaan' => PermintaanBahan::generateNomor(),
                'tanggal_permintaan' => now()->toDateString(),
                'user_id' => auth()->id(),
                'status' => 'pending',
                'catatan' => $request->catatan,
            ]);

            foreach ($request->items as $item) {
                PermintaanDetail::create([
                    'permintaan_id' => $permintaan->id,
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'jumlah_diminta' => $item['jumlah_diminta'],
                ]);
            }
        });

        return redirect()->route('permintaan-bahan.index')
            ->with('success', 'Permintaan bahan berhasil diajukan.');
    }

    public function show(PermintaanBahan $permintaan)
    {
        $permintaan->load('pengaju', 'pemroses', 'detail.bahanBaku');

        if (auth()->user()->isProduksi() && $permintaan->user_id !== auth()->id()) {
            abort(403);
        }

        return view('permintaan-bahan.show', compact('permintaan'));
    }

    public function approve(Request $request, PermintaanBahan $permintaan)
    {
        if (!$permintaan->isPending()) {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.detail_id' => 'required|exists:permintaan_details,id',
            'items.*.jumlah_disetujui' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $permintaan) {
            foreach ($request->items as $item) {
                $detail = PermintaanDetail::findOrFail($item['detail_id']);
                $detail->update(['jumlah_disetujui' => $item['jumlah_disetujui']]);

                if ($item['jumlah_disetujui'] > 0) {
                    $detail->bahanBaku->kurangiStok($item['jumlah_disetujui']);

                    $pemakaian = PemakaianBahanBaku::create([
                        'nomor_transaksi' => PemakaianBahanBaku::generateNomor(),
                        'tanggal_pemakaian' => now()->toDateString(),
                        'user_id' => auth()->id(),
                        'keterangan' => 'Dari permintaan ' . $permintaan->nomor_permintaan,
                    ]);

                    PemakaianDetail::create([
                        'pemakaian_id' => $pemakaian->id,
                        'bahan_baku_id' => $detail->bahan_baku_id,
                        'jumlah' => $item['jumlah_disetujui'],
                    ]);
                }
            }

            $permintaan->update([
                'status' => 'disetujui',
                'diproses_oleh' => auth()->id(),
            ]);
        });

        return redirect()->route('permintaan-bahan.index')
            ->with('success', 'Permintaan berhasil disetujui dan stok telah diperbarui.');
    }

    public function tolak(Request $request, PermintaanBahan $permintaan)
    {
        if (!$permintaan->isPending()) {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);

        $permintaan->update([
            'status' => 'ditolak',
            'catatan' => $request->catatan ?? $permintaan->catatan,
            'diproses_oleh' => auth()->id(),
        ]);

        return redirect()->route('permintaan-bahan.index')
            ->with('success', 'Permintaan telah ditolak.');
    }
}