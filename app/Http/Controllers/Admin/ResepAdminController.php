<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use Illuminate\Http\Request;

class ResepAdminController extends Controller
{
    public function index()
    {
        $resep = Resep::with(['kunjungan.pasien', 'dokter', 'detail.obat'])
            ->orderByRaw("
                CASE status 
                    WHEN 'menunggu' THEN 1 
                    WHEN 'diproses' THEN 2 
                    WHEN 'selesai' THEN 3 
                    ELSE 4 
                END ASC
            ")
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
        foreach ($resep->detail as $item) {
            if ($item->obat->stok < $item->jumlah) {
                return back()->with('error', "Stok {$item->obat->nama_obat} tidak cukup. Stok tersisa: {$item->obat->stok}");
            }
        }

        foreach ($resep->detail as $item) {
            $item->obat->decrement('stok', $item->jumlah);
        }

        $resep->update([
            'status'        => 'selesai',
            'diserahkan_at' => now(),
        ]);

        return redirect()->route('admin.resep.index')->with('success', 'Resep berhasil diproses, stok obat telah dikurangi.');
    }
}