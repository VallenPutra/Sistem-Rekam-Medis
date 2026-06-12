<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

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