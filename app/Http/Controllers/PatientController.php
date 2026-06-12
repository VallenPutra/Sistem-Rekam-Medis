<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PatientController extends Controller
{
    // Daftar semua pasien + pencarian
    public function index(Request $request)
    {
        $search = $request->get('search');

        $patients = Patient::when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%');
        })
        ->orderBy('nama')
        ->paginate(10)
        ->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    // Form tambah pasien
    public function create()
    {
        return view('patients.create');
    }

    // Simpan pasien baru
    public function store(Request $request)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
            'no_telepon'    => 'required|string|max:20',
        ], [
            'nama.required'          => 'Nama pasien wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'no_telepon.required'    => 'Nomor telepon wajib diisi.',
        ]);

        Patient::create($request->all());

        return redirect()->route('patients.index')
            ->with('success', 'Pasien berhasil ditambahkan!');
    }

    // Detail pasien
    public function show(Patient $patient)
    {
        $medicalRecords = $patient->medicalRecords()
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(5);

        return view('patients.show', compact('patient', 'medicalRecords'));
    }

    // Form edit pasien
    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    // Update data pasien
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'nama'          => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
            'no_telepon'    => 'required|string|max:20',
        ], [
            'nama.required'          => 'Nama pasien wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before'   => 'Tanggal lahir harus sebelum hari ini.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'alamat.required'        => 'Alamat wajib diisi.',
            'no_telepon.required'    => 'Nomor telepon wajib diisi.',
        ]);

        $patient->update($request->all());

        return redirect()->route('patients.index')
            ->with('success', 'Data pasien berhasil diperbarui!');
    }

    // Hapus pasien
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', 'Pasien berhasil dihapus!');
    }
}
