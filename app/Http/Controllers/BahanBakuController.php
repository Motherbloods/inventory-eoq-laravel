<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Http\Requests\StoreBahanBakuRequest;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBaku::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_bahan', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_bahan', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filter === 'kritis') {
            $query->whereColumn('stok_saat_ini', '<=', 'stok_minimum');
        }

        $bahanBakus = $query->orderBy('nama_bahan')->paginate(15)->withQueryString();
        $kategoris = BahanBaku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        $totalKritis = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();

        return view('bahan-baku.index', compact('bahanBakus', 'kategoris', 'totalKritis'));
    }

    public function create()
    {
        $kategoris = BahanBaku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        return view('bahan-baku.create', compact('kategoris'));
    }

    public function store(StoreBahanBakuRequest $request)
    {
        BahanBaku::create($request->validated());

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function show(BahanBaku $bahanBaku)
    {
        $bahanBaku->load(['eoqSetting', 'pembelianDetail.pembelian', 'pemakaianDetail.pemakaian', 'koreksiStok.user']);

        $riwayat = $this->getRiwayatStok($bahanBaku);

        return view('bahan-baku.show', compact('bahanBaku', 'riwayat'));
    }

    public function edit(BahanBaku $bahanBaku)
    {
        $kategoris = BahanBaku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
        return view('bahan-baku.edit', compact('bahanBaku', 'kategoris'));
    }

    public function update(StoreBahanBakuRequest $request, BahanBaku $bahanBaku)
    {
        $bahanBaku->update($request->validated());

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Data bahan baku berhasil diperbarui.');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        // Cegah hapus jika masih ada relasi transaksi
        if ($bahanBaku->pembelianDetail()->exists() || $bahanBaku->pemakaianDetail()->exists()) {
            return back()->with('error', 'Bahan baku tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        }

        $bahanBaku->delete();

        return redirect()->route('bahan-baku.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }

    public function stokProduksi(Request $request)
    {
        $query = BahanBaku::query();

        if ($request->filled('search')) {
            $query->where('nama_bahan', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $bahanBakus = $query->orderBy('nama_bahan')->paginate(20)->withQueryString();
        $kategoris = BahanBaku::select('kategori')->distinct()->orderBy('kategori')->pluck('kategori');

        return view('bahan-baku.stok-produksi', compact('bahanBakus', 'kategoris'));
    }

    private function getRiwayatStok(BahanBaku $bahan): \Illuminate\Support\Collection
    {
        $masuk = $bahan->pembelianDetail->map(fn($d) => [
            'tanggal' => $d->pembelian->tanggal_pembelian,
            'jenis' => 'masuk',
            'keterangan' => 'Pembelian ' . $d->pembelian->nomor_transaksi,
            'jumlah' => $d->jumlah,
        ]);

        $keluar = $bahan->pemakaianDetail->map(fn($d) => [
            'tanggal' => $d->pemakaian->tanggal_pemakaian,
            'jenis' => 'keluar',
            'keterangan' => 'Pemakaian ' . $d->pemakaian->nomor_transaksi,
            'jumlah' => $d->jumlah,
        ]);

        $koreksi = $bahan->koreksiStok->map(fn($k) => [
            'tanggal' => $k->tanggal_koreksi,
            'jenis' => $k->selisih >= 0 ? 'masuk' : 'keluar',
            'keterangan' => 'Koreksi: ' . $k->alasan,
            'jumlah' => abs($k->selisih),
        ]);

        return $masuk->merge($keluar)->merge($koreksi)->sortByDesc('tanggal')->values();
    }
}