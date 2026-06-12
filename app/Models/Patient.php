<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'no_telepon'])]
class Patient extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relasi: satu pasien memiliki banyak rekam medis
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Accessor: hitung umur pasien
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age;
    }
}