<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
    public function index()
    {
        $kamar = Kamar::withCount(['rawatInap as terisi' => fn($q) => $q->where('status', 'aktif')])
            ->orderBy('kelas')->orderBy('nama_kamar')->get();
        return view('admin.kamar.index', compact('kamar'));
    }

    public function create()
    {
        return view('admin.kamar.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kamar'     => 'required|string|max:50',
            'kelas'          => 'required|string|max:20',
            'tarif_per_hari' => 'required|numeric|min:0',
            'kapasitas'      => 'required|integer|min:1',
            'keterangan'     => 'nullable|string',
        ]);
        Kamar::create($request->all());
        return redirect()->route('admin.kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar)
    {
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $request->validate([
            'nama_kamar'     => 'required|string|max:50',
            'kelas'          => 'required|string|max:20',
            'tarif_per_hari' => 'required|numeric|min:0',
            'kapasitas'      => 'required|integer|min:1',
            'status'         => 'required|in:tersedia,terisi,maintenance',
        ]);
        $kamar->update($request->all());
        return redirect()->route('admin.kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }

    /**
     * TAMBAHAN: Fungsi Hapus Data Kamar
     */
    public function destroy(Kamar $kamar)
    {
        // Validasi Keamanan: Hitung jumlah pasien aktif di kamar ini saat ini
        $pasienAktif = $kamar->rawatInap()->where('status', 'aktif')->count();

        if ($pasienAktif > 0) {
            return redirect()->route('admin.kamar.index')
                ->with('error', 'Kamar tidak bisa dihapus karena saat ini sedang diisi oleh pasien rawat inap.');
        }

        // Jika kosong, hapus permanen dari database
        $kamar->delete();

        return redirect()->route('admin.kamar.index')->with('success', 'Data kamar berhasil dihapus.');
    }
}