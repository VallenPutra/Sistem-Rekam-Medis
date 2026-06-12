<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Kunjungan, Obat, RawatInap};

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_pasien_hari_ini' => Kunjungan::whereDate('tanggal_kunjungan', today())->count(),
            'total_rawat_inap'      => RawatInap::where('status', 'aktif')->count(),
            'resep_menunggu'        => \App\Models\Resep::where('status', 'menunggu')->count(),
            'antrian_hari_ini'      => Kunjungan::with(['pasien', 'dokter'])
                                            ->whereDate('tanggal_kunjungan', today())
                                            ->orderBy('nomor_antrian')
                                            ->get(),
            'obat_stok_rendah'      => Obat::whereRaw('stok <= stok_minimum')->count(),
        ];
        return view('admin.dashboard', $data);
    }
}