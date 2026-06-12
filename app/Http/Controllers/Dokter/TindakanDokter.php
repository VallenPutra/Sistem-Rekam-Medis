<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Tindakan, KunjunganTindakan};
use Illuminate\Http\Request;

class TindakanDokterController extends Controller
{
    public function store(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'tindakan_id' => 'required|exists:tindakan,id',
            'jumlah'      => 'required|integer|min:1',
            'keterangan'  => 'nullable|string',
        ]);

        $tindakan = Tindakan::findOrFail($request->tindakan_id);
        $subtotal = $tindakan->tarif * $request->jumlah;

        KunjunganTindakan::create([
            'kunjungan_id' => $kunjungan->id,
            'tindakan_id'  => $tindakan->id,
            'jumlah'       => $request->jumlah,
            'tarif'        => $tindakan->tarif,
            'subtotal'     => $subtotal,
            'keterangan'   => $request->keterangan,
        ]);

        return back()->with('success', 'Tindakan berhasil ditambahkan.');
    }

    public function destroy(KunjunganTindakan $tindakan)
    {
        abort_if($tindakan->kunjungan->dokter_id !== auth()->id(), 403);
        $tindakan->delete();
        return back()->with('success', 'Tindakan dihapus.');
    }
}