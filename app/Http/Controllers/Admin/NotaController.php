<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kunjungan::with(['pasien', 'dokter'])
            ->where('status_bayar', 'sudah'); // Hanya mengambil yang sudah lunas

        // Fitur pencarian berdasarkan nama pasien atau nomor rekam medis
        if ($request->filled('search')) {
            $query->whereHas('pasien', function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('no_rm', 'like', "%{$request->search}%");
            });
        }

        // Urutkan berdasarkan waktu pembayaran terbaru
        $nota = $query->orderBy('bayar_at', 'desc')->paginate(15)->withQueryString();

        return view('admin.nota.index', compact('nota'));
    }

    public function show(Kunjungan $kunjungan)
    {
        // Proteksi keamanan: Jika belum bayar, jangan izinkan masuk halaman ini
        abort_if($kunjungan->status_bayar !== 'sudah', 404);

        // Load semua relasi yang dibutuhkan untuk rincian biaya (sesuai relasi KasirController)
        $kunjungan->load([
            'pasien', 'dokter', 'resep.detail.obat',
            'tindakan.tindakan', 'rawatInap.kamar'
        ]);

        $total = $kunjungan->total_biaya;

        return view('admin.nota.show', compact('kunjungan', 'total'));
    }
}