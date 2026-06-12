<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Patient;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPasien       = Patient::count();
        $totalRekamMedis   = MedicalRecord::count();
        $totalObat         = Medicine::count();
        $kunjunganTerbaru  = MedicalRecord::with('patient')
            ->orderBy('tanggal_kunjungan', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalPasien',
            'totalRekamMedis',
            'totalObat',
            'kunjunganTerbaru'
        ));
    }
}