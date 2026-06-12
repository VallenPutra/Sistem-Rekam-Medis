<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['patient_id', 'tanggal_kunjungan', 'keluhan', 'diagnosa', 'tindakan', 'catatan'])]
class MedicalRecord extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    //Relasi: satu rekam medis dimiliki oleh satu pasien
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}