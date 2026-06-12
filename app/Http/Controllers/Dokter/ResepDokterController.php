<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Resep, ResepDetail, Obat};
use Illuminate\Http\Request;

class ResepDokterController extends Controller
{
    public function store(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'obat'                  => 'required|array|min:1',
            'obat.*.obat_id'        => 'required|exists:obat,id',
            'obat.*.jumlah'         => 'required|integer|min:1',
            'obat.*.aturan_pakai'   => 'required|string',
            'catatan'               => 'nullable|string',
        ]);

        $kunjungan->resep()->delete();

        $totalHarga = 0;
        $resep = Resep::create([
            'kunjungan_id' => $kunjungan->id,
            'dokter_id'    => auth()->id(),
            'catatan'      => $request->catatan,
            'status'       => 'menunggu',
        ]);

        foreach ($request->obat as $item) {
            $obat       = Obat::findOrFail($item['obat_id']);
            $subtotal   = $obat->harga_jual * $item['jumlah'];
            $totalHarga += $subtotal;

            ResepDetail::create([
                'resep_id'    => $resep->id,
                'obat_id'     => $obat->id,
                'jumlah'      => $item['jumlah'],
                'aturan_pakai'=> $item['aturan_pakai'],
                'harga_satuan'=> $obat->harga_jual,
                'subtotal'    => $subtotal,
            ]);
        }

        $resep->update(['total_harga_obat' => $totalHarga]);

        return back()->with('success', 'Resep berhasil dikirim ke apotek.');
    }
}