<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Pemasok;
use App\Models\PembelianBahanBaku;
use App\Models\PembelianDetail;
use App\Http\Requests\StorePembelianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $query = PembelianBahanBaku::with('pemasok', 'user');

        if ($request->filled('search')) {
            $query->where('nomor_transaksi', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('pemasok_id')) {
            $query->where('pemasok_id', $request->pemasok_id);
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_pembelian', [$request->dari, $request->sampai]);
        }

        $pembelians = $query->latest('tanggal_pembelian')->paginate(15)->withQueryString();
        $pemasoks = Pemasok::where('is_active', true)->orderBy('nama_pemasok')->get();

        return view('pembelian.index', compact('pembelians', 'pemasoks'));
    }

    public function create()
    {
        $pemasoks = Pemasok::where('is_active', true)->orderBy('nama_pemasok')->get();
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();

        return view('pembelian.create', compact('pemasoks', 'bahanBakus'));
    }

    public function store(StorePembelianRequest $request)
    {
        DB::transaction(function () use ($request) {
            $totalHarga = collect($request->items)->sum(
                fn($item) => $item['jumlah'] * $item['harga_satuan']
            );

            $pembelian = PembelianBahanBaku::create([
                'nomor_transaksi' => PembelianBahanBaku::generateNomor(),
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'pemasok_id' => $request->pemasok_id,
                'user_id' => auth()->id(),
                'total_harga' => $totalHarga,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['jumlah'] * $item['harga_satuan'],
                ]);

                BahanBaku::find($item['bahan_baku_id'])->tambahStok($item['jumlah']);
            }
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Transaksi pembelian berhasil disimpan.');
    }

    public function show(PembelianBahanBaku $pembelian)
    {
        $pembelian->load('pemasok', 'user', 'detail.bahanBaku');

        return view('pembelian.show', compact('pembelian'));
    }

    public function edit(PembelianBahanBaku $pembelian)
    {
        $pembelian->load('detail.bahanBaku');
        $pemasoks = Pemasok::where('is_active', true)->orderBy('nama_pemasok')->get();
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();

        return view('pembelian.edit', compact('pembelian', 'pemasoks', 'bahanBakus'));
    }

    public function update(StorePembelianRequest $request, PembelianBahanBaku $pembelian)
    {
        DB::transaction(function () use ($request, $pembelian) {
            foreach ($pembelian->detail as $detail) {
                BahanBaku::find($detail->bahan_baku_id)->kurangiStok($detail->jumlah);
            }

            $pembelian->detail()->delete();

            $totalHarga = collect($request->items)->sum(
                fn($item) => $item['jumlah'] * $item['harga_satuan']
            );

            $pembelian->update([
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'pemasok_id' => $request->pemasok_id,
                'total_harga' => $totalHarga,
                'keterangan' => $request->keterangan,
            ]);

            foreach ($request->items as $item) {
                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'bahan_baku_id' => $item['bahan_baku_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['jumlah'] * $item['harga_satuan'],
                ]);

                BahanBaku::find($item['bahan_baku_id'])->tambahStok($item['jumlah']);
            }
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Transaksi pembelian berhasil diperbarui.');
    }

    public function destroy(PembelianBahanBaku $pembelian)
    {
        DB::transaction(function () use ($pembelian) {
            foreach ($pembelian->detail as $detail) {
                BahanBaku::find($detail->bahan_baku_id)->kurangiStok($detail->jumlah);
            }
            $pembelian->delete();
        });

        return redirect()->route('pembelian.index')
            ->with('success', 'Transaksi pembelian berhasil dihapus.');
    }
}