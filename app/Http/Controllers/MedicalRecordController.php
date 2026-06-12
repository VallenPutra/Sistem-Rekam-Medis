<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MedicalRecordController extends Controller
{
    // Daftar semua rekam medis
    public function index(Request $request)
    {
        $search = $request->get('search');

        $medicalRecords = MedicalRecord::with('patient')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('patient', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('tanggal_kunjungan', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('medical-records.index', compact('medicalRecords', 'search'));
    }

    // Form tambah rekam medis
    public function create()
    {
        $patients = Patient::orderBy('nama')->get();
        return view('medical-records.create', compact('patients'));
    }

    // Simpan rekam medis baru
    public function store(Request $request)
    {
        $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'tanggal_kunjungan' => 'required|date',
            'keluhan'           => 'required|string',
            'diagnosa'          => 'required|string',
            'tindakan'          => 'required|string',
            'catatan'           => 'nullable|string',
        ], [
            'patient_id.required'        => 'Pasien wajib dipilih.',
            'patient_id.exists'          => 'Pasien tidak ditemukan.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'keluhan.required'           => 'Keluhan wajib diisi.',
            'diagnosa.required'          => 'Diagnosa wajib diisi.',
            'tindakan.required'          => 'Tindakan wajib diisi.',
        ]);

        MedicalRecord::create($request->all());

        return redirect()->route('medical-records.index')
            ->with('success', 'Rekam medis berhasil ditambahkan!');
    }

    // Detail rekam medis
    public function show(MedicalRecord $medicalRecord)
    {
        return view('medical-records.show', compact('medicalRecord'));
    }

    // Form edit rekam medis
    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::orderBy('nama')->get();
        return view('medical-records.edit', compact('medicalRecord', 'patients'));
    }

    // Update rekam medis
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'tanggal_kunjungan' => 'required|date',
            'keluhan'           => 'required|string',
            'diagnosa'          => 'required|string',
            'tindakan'          => 'required|string',
            'catatan'           => 'nullable|string',
        ], [
            'patient_id.required'        => 'Pasien wajib dipilih.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'keluhan.required'           => 'Keluhan wajib diisi.',
            'diagnosa.required'          => 'Diagnosa wajib diisi.',
            'tindakan.required'          => 'Tindakan wajib diisi.',
        ]);

        $medicalRecord->update($request->all());

        return redirect()->route('medical-records.index')
            ->with('success', 'Rekam medis berhasil diperbarui!');
    }

    // Hapus rekam medis
    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return redirect()->route('medical-records.index')
            ->with('success', 'Rekam medis berhasil dihapus!');
    }
}
