<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\KoreksiStok;
use App\Http\Requests\StoreKoreksiStokRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KoreksiStokController extends Controller
{
    public function index(Request $request)
    {
        $query = KoreksiStok::with('bahanBaku', 'user');

        if ($request->filled('search')) {
            $query->where('nomor_transaksi', 'like', '%' . $request->search . '%')
                ->orWhereHas('bahanBaku', fn($q) => $q->where('nama_bahan', 'like', '%' . $request->search . '%'));
        }

        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('tanggal_koreksi', [$request->dari, $request->sampai]);
        }

        $koreksis = $query->latest('tanggal_koreksi')->paginate(15)->withQueryString();

        return view('koreksi-stok.index', compact('koreksis'));
    }

    public function create()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();
        return view('koreksi-stok.create', compact('bahanBakus'));
    }

    public function store(StoreKoreksiStokRequest $request)
    {
        DB::transaction(function () use ($request) {
            $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
            $jumlahSebelum = $bahan->stok_saat_ini;
            $jumlahSesudah = $request->jumlah_sesudah;
            $selisih = $jumlahSesudah - $jumlahSebelum;

            KoreksiStok::create([
                'nomor_transaksi' => KoreksiStok::generateNomor(),
                'tanggal_koreksi' => $request->tanggal_koreksi,
                'bahan_baku_id' => $request->bahan_baku_id,
                'jumlah_sebelum' => $jumlahSebelum,
                'jumlah_sesudah' => $jumlahSesudah,
                'selisih' => $selisih,
                'alasan' => $request->alasan,
                'user_id' => auth()->id(),
            ]);

            // Langsung set stok ke nilai baru
            $bahan->update(['stok_saat_ini' => $jumlahSesudah]);
        });

        return redirect()->route('koreksi-stok.index')
            ->with('success', 'Koreksi stok berhasil disimpan.');
    }

    public function show(KoreksiStok $koreksiStok)
    {
        $koreksiStok->load('bahanBaku', 'user');
        return view('koreksi-stok.show', compact('koreksiStok'));
    }
}