<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Kamar, RawatInap};
use Illuminate\Http\Request;

class RawatInapDokterController extends Controller
{
    public function pilihKamar(Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);
        $kamar = Kamar::where('status', 'tersedia')->get();
        return view('dokter.rawat_inap_admisi', compact('kunjungan', 'kamar'));
    }

    public function admisi(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'kamar_id'         => 'required|exists:kamar,id',
            'instruksi_dokter' => 'nullable|string',
        ]);

        $kamar = Kamar::findOrFail($request->kamar_id);
        if ($kamar->status !== 'tersedia') {
            return back()->with('error', 'Kamar tidak tersedia.');
        }

        RawatInap::create([
            'kunjungan_id'     => $kunjungan->id,
            'pasien_id'        => $kunjungan->pasien_id,
            'kamar_id'         => $kamar->id,
            'dokter_id'        => auth()->id(),
            'tanggal_masuk'    => today(),
            'instruksi_dokter' => $request->instruksi_dokter,
            'status'           => 'aktif',
        ]);

        $kamar->update(['status' => 'terisi']);
        $kunjungan->update(['status' => 'rawat_inap', 'jenis_kunjungan' => 'rawat_inap']);

        return redirect()->route('dokter.dashboard')->with('success', 'Pasien berhasil dipindahkan ke rawat inap.');
    }

    public function keluarkan(Request $request, RawatInap $rawatInap)
    {
        abort_if($rawatInap->dokter_id !== auth()->id(), 403);

        $lama            = $rawatInap->tanggal_masuk->diffInDays(today()) ?: 1;
        $totalBiayaKamar = $lama * $rawatInap->kamar->tarif_per_hari;

        $rawatInap->update([
            'tanggal_keluar'    => today(),
            'lama_hari'         => $lama,
            'total_biaya_kamar' => $totalBiayaKamar,
            'status'            => 'keluar',
        ]);

        $rawatInap->kamar->update(['status' => 'tersedia']);
        $rawatInap->kunjungan->update(['status' => 'selesai']);

        return redirect()->route('dokter.dashboard')->with('success', 'Pasien berhasil dikeluarkan dari rawat inap.');
    }
}