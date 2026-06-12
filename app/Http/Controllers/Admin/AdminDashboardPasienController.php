<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Pasien, User, Obat, RawatInap};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_pasien_hari_ini'  => Kunjungan::whereDate('tanggal_kunjungan', today())->count(),
            'total_rawat_inap'       => RawatInap::where('status', 'aktif')->count(),
            'resep_menunggu'         => \App\Models\Resep::where('status', 'menunggu')->count(),
            'pembayaran_belum'       => Kunjungan::where('status', 'selesai')->where('status_bayar', 'belum')->count(),
            'antrian_hari_ini'       => Kunjungan::with(['pasien', 'dokter'])
                                            ->whereDate('tanggal_kunjungan', today())
                                            ->orderBy('nomor_antrian')
                                            ->get(),
            'obat_stok_rendah'       => Obat::whereRaw('stok <= stok_minimum')->count(),
        ];
        return view('admin.dashboard', $data);
    }
}

class AkunController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.akun.index', compact('users'));
    }

    public function create()
    {
        return view('admin.akun.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed',
            'role'      => 'required|in:admin,dokter',
            'no_hp'     => 'nullable|string|max:15',
            'spesialis' => 'nullable|string|max:100',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'no_hp'     => $request->no_hp,
            'spesialis' => $request->spesialis,
            'aktif'     => true,
        ]);

        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.akun.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'role'      => 'required|in:admin,dokter',
            'no_hp'     => 'nullable|string|max:15',
            'spesialis' => 'nullable|string|max:100',
            'password'  => 'nullable|min:6|confirmed',
        ]);

        $data = $request->except(['password', 'password_confirmation', '_token', '_method']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.akun.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function toggleAktif(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }
        $user->update(['aktif' => !$user->aktif]);
        return back()->with('success', 'Status akun berhasil diubah.');
    }
}

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $query = Pasien::query();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('no_rm', 'like', "%$search%")
                  ->orWhere('nik', 'like', "%$search%");
            });
        }
        $pasien = $query->latest()->paginate(15)->withQueryString();
        return view('admin.pasien.index', compact('pasien'));
    }

    public function create()
    {
        return view('admin.pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'             => 'required|string|max:100',
            'jenis_kelamin'    => 'required|in:L,P',
            'tanggal_lahir'    => 'required|date',
            'nik'              => 'nullable|string|size:16|unique:pasien',
            'no_hp'            => 'nullable|string|max:15',
            'alamat'           => 'nullable|string',
            'golongan_darah'   => 'nullable|in:A,B,AB,O',
            'jenis_pembayaran' => 'required|in:umum,bpjs',
            'no_bpjs'          => 'nullable|required_if:jenis_pembayaran,bpjs|string|max:20',
        ]);

        $pasien = Pasien::create(array_merge(
            $request->all(),
            ['no_rm' => Pasien::generateNoRM()]
        ));

        return redirect()->route('admin.pasien.show', $pasien)
            ->with('success', "Pasien berhasil didaftarkan. No RM: {$pasien->no_rm}");
    }

    public function show(Pasien $pasien)
    {
        $riwayat = $pasien->kunjungan()->with(['dokter', 'resep', 'tindakan.tindakan'])->latest()->get();
        return view('admin.pasien.show', compact('pasien', 'riwayat'));
    }

    public function edit(Pasien $pasien)
    {
        return view('admin.pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nama'             => 'required|string|max:100',
            'jenis_kelamin'    => 'required|in:L,P',
            'tanggal_lahir'    => 'required|date',
            'nik'              => 'nullable|string|size:16|unique:pasien,nik,' . $pasien->id,
            'no_hp'            => 'nullable|string|max:15',
            'alamat'           => 'nullable|string',
            'golongan_darah'   => 'nullable|in:A,B,AB,O',
            'jenis_pembayaran' => 'required|in:umum,bpjs',
            'no_bpjs'          => 'nullable|required_if:jenis_pembayaran,bpjs',
        ]);

        $pasien->update($request->all());
        return redirect()->route('admin.pasien.show', $pasien)->with('success', 'Data pasien berhasil diperbarui.');
    }
}
