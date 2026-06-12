<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Pasien, User, Resep, ResepDetail, Obat, Kamar, RawatInap, KunjunganTindakan, Rontgen};
use App\Models\Tindakan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $antrian = Kunjungan::with('pasien')
            ->where('dokter_id', auth()->id())
            ->whereDate('tanggal_kunjungan', today())
            ->whereIn('status', ['menunggu', 'sedang_diperiksa'])
            ->orderBy('nomor_antrian')
            ->get();

        $selesaiHariIni = Kunjungan::where('dokter_id', auth()->id())
            ->whereDate('tanggal_kunjungan', today())
            ->where('status', 'selesai')
            ->count();

        $rawatInapAktif = RawatInap::where('dokter_id', auth()->id())
            ->where('status', 'aktif')
            ->with('pasien')
            ->get();

        return view('dokter.dashboard', compact('antrian', 'selesaiHariIni', 'rawatInapAktif'));
    }
}

// ============================================================
// RekamMedisController – Input rekam medis & periksa pasien
// ============================================================
class RekamMedisController extends Controller
{
    public function periksa(Kunjungan $kunjungan)
    {
        // Pastikan kunjungan milik dokter ini
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
            'anamnesis'        => 'required|string',
            'pemeriksaan_fisik'=> 'required|string',
            'diagnosis'        => 'required|string|max:255',
            'kode_icd10'       => 'nullable|string|max:10',
            'catatan_dokter'   => 'nullable|string',
        ]);

        $kunjungan->update([
            'anamnesis'        => $request->anamnesis,
            'pemeriksaan_fisik'=> $request->pemeriksaan_fisik,
            'diagnosis'        => $request->diagnosis,
            'kode_icd10'       => $request->kode_icd10,
            'catatan_dokter'   => $request->catatan_dokter,
            'status'           => 'selesai',
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

// ============================================================
// ResepDokterController – E-Prescribing
// ============================================================
class ResepDokterController extends Controller
{
    public function store(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'obat'              => 'required|array|min:1',
            'obat.*.obat_id'    => 'required|exists:obat,id',
            'obat.*.jumlah'     => 'required|integer|min:1',
            'obat.*.aturan_pakai' => 'required|string',
            'catatan'           => 'nullable|string',
        ]);

        // Hapus resep lama jika ada (update resep)
        $kunjungan->resep()->delete();

        $totalHarga = 0;
        $resep = Resep::create([
            'kunjungan_id' => $kunjungan->id,
            'dokter_id'    => auth()->id(),
            'catatan'      => $request->catatan,
            'status'       => 'menunggu',
        ]);

        foreach ($request->obat as $item) {
            $obat = Obat::findOrFail($item['obat_id']);
            $subtotal = $obat->harga_jual * $item['jumlah'];
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

// ============================================================
// TindakanDokterController – Input tindakan medis
// ============================================================
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

// ============================================================
// RontgenController – Upload & analisis foto rontgen
// ============================================================
class RontgenController extends Controller
{
    public function store(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'foto_rontgen'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bagian_tubuh'  => 'required|string|max:50',
            'hasil_analisis'=> 'nullable|string',
        ]);

        $path = $request->file('foto_rontgen')->store('rontgen', 'public');

        Rontgen::create([
            'kunjungan_id'   => $kunjungan->id,
            'dokter_id'      => auth()->id(),
            'file_path'      => $path,
            'bagian_tubuh'   => $request->bagian_tubuh,
            'hasil_analisis' => $request->hasil_analisis,
        ]);

        return back()->with('success', 'Foto rontgen berhasil diunggah.');
    }
}

// ============================================================
// RawatInapDokterController – Admission rawat inap
// ============================================================
class RawatInapDokterController extends Controller
{
    public function admisi(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'kamar_id'          => 'required|exists:kamar,id',
            'instruksi_dokter'  => 'nullable|string',
        ]);

        $kamar = Kamar::findOrFail($request->kamar_id);
        if ($kamar->status !== 'tersedia') {
            return back()->with('error', 'Kamar tidak tersedia.');
        }

        RawatInap::create([
            'kunjungan_id'      => $kunjungan->id,
            'pasien_id'         => $kunjungan->pasien_id,
            'kamar_id'          => $kamar->id,
            'dokter_id'         => auth()->id(),
            'tanggal_masuk'     => today(),
            'instruksi_dokter'  => $request->instruksi_dokter,
            'status'            => 'aktif',
        ]);

        $kamar->update(['status' => 'terisi']);
        $kunjungan->update(['status' => 'rawat_inap', 'jenis_kunjungan' => 'rawat_inap']);

        return redirect()->route('dokter.dashboard')->with('success', 'Pasien berhasil dipindahkan ke rawat inap.');
    }

    public function keluarkan(Request $request, RawatInap $rawatInap)
    {
        abort_if($rawatInap->dokter_id !== auth()->id(), 403);

        $lama = $rawatInap->tanggal_masuk->diffInDays(today()) ?: 1;
        $totalBiayaKamar = $lama * $rawatInap->kamar->tarif_per_hari;

        $rawatInap->update([
            'tanggal_keluar'   => today(),
            'lama_hari'        => $lama,
            'total_biaya_kamar'=> $totalBiayaKamar,
            'status'           => 'keluar',
        ]);

        $rawatInap->kamar->update(['status' => 'tersedia']);
        $rawatInap->kunjungan->update(['status' => 'selesai']);

        return redirect()->route('dokter.dashboard')->with('success', 'Pasien berhasil dikeluarkan dari rawat inap.');
    }

    public function pilihKamar(Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);
        $kamar = Kamar::where('status', 'tersedia')->get();
        return view('dokter.rawat_inap_admisi', compact('kunjungan', 'kamar'));
    }
}
