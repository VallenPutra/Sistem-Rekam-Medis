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
}

class ResepAdminController extends Controller
{
    public function index()
    {
        $resep = Resep::with(['kunjungan.pasien', 'dokter', 'detail.obat'])
            ->orderByRaw("FIELD(status, 'menunggu', 'diproses', 'selesai')")
            ->latest()
            ->paginate(15);
        return view('admin.resep.index', compact('resep'));
    }

    public function show(Resep $resep)
    {
        $resep->load(['kunjungan.pasien', 'dokter', 'detail.obat']);
        return view('admin.resep.show', compact('resep'));
    }

    public function proses(Resep $resep)
    {
        // Cek stok semua obat
        foreach ($resep->detail as $item) {
            if ($item->obat->stok < $item->jumlah) {
                return back()->with('error', "Stok {$item->obat->nama_obat} tidak cukup. Stok tersisa: {$item->obat->stok}");
            }
        }

        // Potong stok obat
        foreach ($resep->detail as $item) {
            $item->obat->decrement('stok', $item->jumlah);
        }

        $resep->update([
            'status'       => 'selesai',
            'diserahkan_at' => now(),
        ]);

        return redirect()->route('admin.resep.index')->with('success', 'Resep berhasil diproses, stok obat telah dikurangi.');
    }
}


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
            'nama_obat'          => 'required|string|max:100',
            'satuan'             => 'required|string|max:20',
            'stok'               => 'required|integer|min:0',
            'stok_minimum'       => 'required|integer|min:1',
            'harga_beli'         => 'required|numeric|min:0',
            'harga_jual'         => 'required|numeric|min:0',
        ]);
        $obat->update($request->all());
        return redirect()->route('admin.obat.index')->with('success', 'Data obat berhasil diperbarui.');
    }
}

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
            'nama_kamar'    => 'required|string|max:50',
            'kelas'         => 'required|string|max:20',
            'tarif_per_hari'=> 'required|numeric|min:0',
            'kapasitas'     => 'required|integer|min:1',
            'keterangan'    => 'nullable|string',
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
            'nama_kamar'    => 'required|string|max:50',
            'kelas'         => 'required|string|max:20',
            'tarif_per_hari'=> 'required|numeric|min:0',
            'kapasitas'     => 'required|integer|min:1',
            'status'        => 'required|in:tersedia,terisi,maintenance',
        ]);
        $kamar->update($request->all());
        return redirect()->route('admin.kamar.index')->with('success', 'Data kamar berhasil diperbarui.');
    }
}

class TindakanController extends Controller
{
    public function index()
    {
        $tindakan = \App\Models\Tindakan::orderBy('nama_tindakan')->paginate(15);
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
        \App\Models\Tindakan::create(array_merge(
            $request->all(),
            ['kode_tindakan' => \App\Models\Tindakan::generateKode()]
        ));
        return redirect()->route('admin.tindakan.index')->with('success', 'Tindakan berhasil ditambahkan.');
    }
}
