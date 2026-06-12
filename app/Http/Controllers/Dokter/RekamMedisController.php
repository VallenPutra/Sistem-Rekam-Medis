<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Pasien, Obat, Tindakan};
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function periksa(Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $kunjungan->update(['status' => 'sedang_diperiksa']);
        $kunjungan->load(['pasien', 'tindakan.tindakan', 'resep.detail.obat', 'rontgen']);

        $tindakanList = Tindakan::orderBy('nama_tindakan')->get();
        $obatList     = Obat::where('stok', '>', 0)->orderBy('nama_obat')->get();

        return view('dokter.rekam_medis', compact('kunjungan', 'tindakanList', 'obatList'));
    }

    public function simpan(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'anamnesis'         => 'required|string',
            'pemeriksaan_fisik' => 'required|string',
            'diagnosis'         => 'required|string|max:255',
            'kode_icd10'        => 'nullable|string|max:10',
            'catatan_dokter'    => 'nullable|string',
        ]);

        $kunjungan->update([
            'anamnesis'         => $request->anamnesis,
            'pemeriksaan_fisik' => $request->pemeriksaan_fisik,
            'diagnosis'         => $request->diagnosis,
            'kode_icd10'        => $request->kode_icd10,
            'catatan_dokter'    => $request->catatan_dokter,
            'status'            => 'selesai',
        ]);

        return redirect()->route('dokter.dashboard')->with('success', 'Rekam medis berhasil disimpan.');
    }

    public function riwayat(Pasien $pasien)
    {
        $riwayat = $pasien->kunjungan()
            ->with(['dokter', 'resep.detail.obat', 'tindakan.tindakan', 'rontgen'])
            ->latest()
            ->get();
        return view('dokter.riwayat', compact('pasien', 'riwayat'));
    }
}