<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use App\Http\Requests\StorePemasokRequest;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemasok::query();

        if ($request->filled('search')) {
            $query->where('nama_pemasok', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'aktif');
        }

        $pemasoks = $query->orderBy('nama_pemasok')->paginate(15)->withQueryString();

        return view('pemasok.index', compact('pemasoks'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(StorePemasokRequest $request)
    {
        Pemasok::create($request->validated());

        return redirect()->route('pemasok.index')
            ->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function show(Pemasok $pemasok)
    {
        $pemasok->loadCount('pembelian');
        $pembelianTerbaru = $pemasok->pembelian()->latest()->limit(10)->get();

        return view('pemasok.show', compact('pemasok', 'pembelianTerbaru'));
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(StorePemasokRequest $request, Pemasok $pemasok)
    {
        $pemasok->update($request->validated());

        return redirect()->route('pemasok.index')
            ->with('success', 'Data pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok)
    {
        if ($pemasok->pembelian()->exists()) {
            return back()->with('error', 'Pemasok tidak dapat dihapus karena masih memiliki riwayat pembelian.');
        }

        $pemasok->delete();

        return redirect()->route('pemasok.index')
            ->with('success', 'Pemasok berhasil dihapus.');
    }
}