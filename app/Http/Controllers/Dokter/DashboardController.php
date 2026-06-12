<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, RawatInap};

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
            ->with(['pasien', 'kamar'])
            ->get();

        return view('dokter.dashboard', compact('antrian', 'selesaiHariIni', 'rawatInapAktif'));
    }
}