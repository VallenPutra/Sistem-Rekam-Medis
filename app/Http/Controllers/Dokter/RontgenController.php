<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Rontgen};
use Illuminate\Http\Request;

class RontgenController extends Controller
{
    public function store(Request $request, Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokter_id !== auth()->id(), 403);

        $request->validate([
            'foto_rontgen'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'bagian_tubuh'   => 'required|string|max:50',
            'hasil_analisis' => 'nullable|string',
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