<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index()
    {
        // Menggunakan LIKE menghindari masalah case-sensitive atau spasi tersembunyi di SQLite
        $tagihan = Kunjungan::with(['pasien', 'dokter'])
            ->where('status', 'selesai')
            ->where('status_bayar', 'LIKE', 'belum%') 
            ->latest()
            ->paginate(15);
            
        return view('admin.kasir.index', compact('tagihan'));
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load([
            'pasien', 'dokter', 'resep.detail.obat',
            'tindakan.tindakan', 'rawatInap.kamar'
        ]);
        $total = $kunjungan->hitungTotalBiaya();
        return view('admin.kasir.show', compact('kunjungan', 'total'));
    }

    public function bayar(Request $request, Kunjungan $kunjungan)
    {
        $total = $kunjungan->hitungTotalBiaya();
        
        // Kita paksa ubah status_bayar menjadi 'sudah'
        $kunjungan->status_bayar = 'sudah';
        $kunjungan->total_biaya = $total;
        $kunjungan->bayar_at = now();
        $kunjungan->save(); // Menggunakan save() langsung ke objek untuk menjamin SQLite mengeksekusinya

        return redirect()->route('admin.kasir.nota', $kunjungan->id)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function nota(Kunjungan $kunjungan)
    {
        $kunjungan->load([
            'pasien', 'dokter', 'resep.detail.obat',
            'tindakan.tindakan', 'rawatInap.kamar'
        ]);
        
        $total = $kunjungan->total_biaya > 0 ? $kunjungan->total_biaya : $kunjungan->hitungTotalBiaya();
        
        return view('admin.kasir.nota', compact('kunjungan', 'total'));
    }
}