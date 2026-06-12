<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $query = Obat::query();
        if ($request->filled('search')) {
            $query->where('nama_obat', 'like', "%{$request->search}%")
                  ->orWhere('kode_obat', 'like', "%{$request->search}%");
        }
        $obat = $query->orderBy('nama_obat')->paginate(15)->withQueryString();
        return view('admin.obat.index', compact('obat'));
    }

    public function create()
    {
        return view('admin.obat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_obat'          => 'required|string|max:100',
            'satuan'             => 'required|string|max:20',
            'stok'               => 'required|integer|min:0',
            'stok_minimum'       => 'required|integer|min:1',
            'harga_beli'         => 'required|numeric|min:0',
            'harga_jual'         => 'required|numeric|min:0',
            'kategori'           => 'nullable|string|max:50',
            'tanggal_kadaluarsa' => 'nullable|date',
        ]);

        Obat::create(array_merge(
            $request->all(),
            ['kode_obat' => Obat::generateKodeObat()]
        ));

        return redirect()->route('admin.obat.index')->with('success', 'Obat berhasil ditambahkan.');
    }

    public function edit(Obat $obat)
    {
        return view('admin.obat.edit', compact('obat'));
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nama_obat'    => 'required|string|max:100',
            'satuan'       => 'required|string|max:20',
            'stok'         => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:1',
            'harga_beli'   => 'required|numeric|min:0',
            'harga_jual'   => 'required|numeric|min:0',
        ]);
        $obat->update($request->all());
        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }

    /**
     * TAMBAHAN: Fungsi Hapus Data Obat
     */
    public function destroy(Obat $obat)
    {
        // Menghapus data obat secara langsung dari database
        $obat->delete();

        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil dihapus.');
    }
}