<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $fillable = ['nama_kamar', 'kelas', 'tarif_per_hari', 'status', 'kapasitas', 'keterangan'];

    public function rawatInap()
    {
        return $this->hasMany(RawatInap::class)->where('status', 'aktif');
    }
}