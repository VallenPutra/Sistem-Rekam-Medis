<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Pasien, User, Resep, ResepDetail, Obat, Kamar, RawatInap};
use Illuminate\Http\Request;

class AntrianController extends Controller
{
    public function index()
    {
        $antrian = Kunjungan::with(['pasien', 'dokter'])
            ->whereDate('tanggal_kunjungan', today())
            ->orderBy('nomor_antrian')
            ->get();
        $dokterList = User::where('role', 'dokter')->where('aktif', true)->get();
        return view('admin.antrian.index', compact('antrian', 'dokterList'));
    }

    public function create()
    {
        $pasienList = Pasien::orderBy('nama')->get();
        $dokterList = User::where('role', 'dokter')->where('aktif', true)->get();
        return view('admin.antrian.create', compact('pasienList', 'dokterList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasien_id'       => 'required|exists:pasien,id',
            'dokter_id'       => 'required|exists:users,id',
            'keluhan_utama'   => 'required|string|max:255',
            'berat_badan'     => 'nullable|numeric',
            'tinggi_badan'    => 'nullable|numeric',
            'tekanan_darah'   => 'nullable|string|max:10',
            'suhu_tubuh'      => 'nullable|numeric',
            'nadi'            => 'nullable|integer',
        ]);

        $noAntrian = Kunjungan::getNextAntrian($request->dokter_id);

        Kunjungan::create([
            'no_kunjungan'    => Kunjungan::generateNoKunjungan(),
            'pasien_id'       => $request->pasien_id,
            'dokter_id'       => $request->dokter_id,
            'tanggal_kunjungan' => today(),
            'nomor_antrian'   => $noAntrian,
            'jenis_kunjungan' => 'rawat_jalan',
            'status'          => 'menunggu',
            'keluhan_utama'   => $request->keluhan_utama,
            'berat_badan'     => $request->berat_badan,
            'tinggi_badan'    => $request->tinggi_badan,
            'tekanan_darah'   => $request->tekanan_darah,
            'suhu_tubuh'      => $request->suhu_tubuh,
            'nadi'            => $request->nadi,
            'jasa_dokter'     => 50000,
        ]);

        return redirect()->route('admin.antrian.index')
            ->with('success', "Pasien berhasil didaftarkan. Nomor antrian: {$noAntrian}");
    }

    /**
     * TAMBAHAN: Fungsi Hapus / Batalkan Antrian Pasien
     */
    public function destroy(Kunjungan $kunjungan)
    {
        // Proteksi: Hanya antrian berstatus 'menunggu' yang boleh dihapus
        if ($kunjungan->status !== 'menunggu') {
            return redirect()->route('admin.antrian.index')
                ->with('error', 'Antrian tidak bisa dihapus karena pasien sedang atau telah diperiksa.');
        }

        // Hapus data kunjungan/antrian dari database
        $kunjungan->delete();

        return redirect()->route('admin.antrian.index')
            ->with('success', 'Antrian pasien berhasil dibatalkan/dihapus.');
    }
}