<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MedicineController extends Controller
{
    // Daftar semua obat
    public function index(Request $request)
    {
        $search = $request->get('search');

        $medicines = Medicine::when($search, function ($query) use ($search) {
            $query->where('nama_obat', 'like', '%' . $search . '%');
        })
        ->orderBy('nama_obat')
        ->paginate(10)
        ->withQueryString();

        return view('medicines.index', compact('medicines', 'search'));
    }

    // Form tambah obat
    public function create()
    {
        return view('medicines.create');
    }

    // Simpan obat baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:100',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'required|string|max:50',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'stok.required'      => 'Stok wajib diisi.',
            'stok.min'           => 'Stok tidak boleh negatif.',
            'satuan.required'    => 'Satuan wajib diisi.',
        ]);

        Medicine::create($request->all());

        return redirect()->route('medicines.index')
            ->with('success', 'Obat berhasil ditambahkan!');
    }

    // Form edit obat
    public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    // Update data obat
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'nama_obat' => 'required|string|max:100',
            'stok'      => 'required|integer|min:0',
            'satuan'    => 'required|string|max:50',
        ], [
            'nama_obat.required' => 'Nama obat wajib diisi.',
            'stok.required'      => 'Stok wajib diisi.',
            'stok.min'           => 'Stok tidak boleh negatif.',
            'satuan.required'    => 'Satuan wajib diisi.',
        ]);

        $medicine->update($request->all());

        return redirect()->route('medicines.index')
            ->with('success', 'Data obat berhasil diperbarui!');
    }

    // Hapus obat
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('success', 'Obat berhasil dihapus!');
    }
}
