<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\PemakaianBahanBaku;
use App\Models\PemakaianDetail;
use App\Http\Requests\StorePemakaianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemakaianController extends Controller
{
    public function index(Request $request)
    {
        $query = PemakaianBahanBaku::with('user');

        if ($request->filled('search')) {
            $query->where('nomor_transaksi', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pemakaian', [$request->dari, $request->sampai]);
        }

        $pemakaians = $query->latest('tanggal_pemakaian')->paginate(15)->withQueryString();

        return view('pemakaian.index', compact('pemakaians'));
    }

    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        return view('pemakaian.create', compact('bahanBakus'));
    }

    public function store(StorePemakaianRequest $request)
    {
        DB::transaction(function () use ($request) {
            $pemakaian = PemakaianBahanBaku::create([
                'nomor_transaksi' => PemakaianBahanBaku::generateNomor(),
                'tanggal_pemakaian' => $request->tanggal_pemakaian,
                'user_id' => auth()->id(),
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                $bahan = BahanBaku::findOrFail($item['bahan_baku_id']);
                $bahan->kurangiStok($item['jumlah']); // lempar exception jika kurang

                PemakaianDetail::create([
                    'pemakaian_id' => $pemakaian->id,
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }
        });

        return redirect()->route('pemakaian.index')
            ->with('success', 'Transaksi pemakaian berhasil disimpan.');
    }

    public function show(PemakaianBahanBaku $pemakaian)
    {
        $pemakaian->load('user', 'detail.bahanBaku');
        return view('pemakaian.show', compact('pemakaian'));
    }

    public function edit(PemakaianBahanBaku $pemakaian)
    {
        $pemakaian->load('detail.bahanBaku');
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        return view('pemakaian.edit', compact('pemakaian', 'bahanBakus'));
    }

    public function update(StorePemakaianRequest $request, PemakaianBahanBaku $pemakaian)
    {
        DB::transaction(function () use ($request, $pemakaian) {
            // Kembalikan stok lama
            foreach ($pemakaian->detail as $detail) {
                BahanBaku::find($detail->bahan_baku_id)->tambahStok($detail->jumlah);
            }
            $pemakaian->detail()->delete();

            $pemakaian->update([
                'tanggal_pemakaian' => $request->tanggal_pemakaian,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                $bahan = BahanBaku::findOrFail($item['bahan_baku_id']);
                $bahan->kurangiStok($item['jumlah']);

                PemakaianDetail::create([
                    'pemakaian_id' => $pemakaian->id,
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }
        });

        return redirect()->route('pemakaian.index')
            ->with('success', 'Transaksi pemakaian berhasil diperbarui.');
    }

    public function destroy(PemakaianBahanBaku $pemakaian)
    {
        DB::transaction(function () use ($pemakaian) {
            foreach ($pemakaian->detail as $detail) {
                BahanBaku::find($detail->bahan_baku_id)->tambahStok($detail->jumlah);
            }
            $pemakaian->delete();
        });

        return redirect()->route('pemakaian.index')
            ->with('success', 'Transaksi pemakaian berhasil dihapus.');
    }
}