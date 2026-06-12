<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tindakan;
use Illuminate\Http\Request;

class TindakanController extends Controller
{
    public function index()
    {
        $tindakan = Tindakan::orderBy('nama_tindakan')->paginate(15);
        return view('admin.tindakan.index', compact('tindakan'));
    }

    public function create()
    {
        return view('admin.tindakan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tindakan' => 'required|string|max:100',
            'tarif'         => 'required|numeric|min:0',
            'keterangan'    => 'nullable|string',
        ]);
        Tindakan::create(array_merge(
            $request->all(),
            ['kode_tindakan' => Tindakan::generateKode()]
        ));
        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil ditambahkan.');
    }

    public function edit(Tindakan $tindakan)
    {
        return view('admin.tindakan.edit', compact('tindakan'));
    }

    public function update(Request $request, Tindakan $tindakan)
    {
        $request->validate([
            'nama_tindakan' => 'required|string|max:100',
            'tarif'         => 'required|numeric|min:0',
            'keterangan'    => 'nullable|string',
        ]);
        $tindakan->update($request->all());
        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil diperbarui.');
    }

    /**
     * TAMBAHAN: Fungsi Hapus Master Data Tindakan
     */
    public function destroy(Tindakan $tindakan)
    {
        // Menghapus data master tindakan secara permanen dari database
        $tindakan->delete();

        return redirect()->route('admin.tindakan.index')->with('success', 'Master data tindakan berhasil dihapus.');
    }
}