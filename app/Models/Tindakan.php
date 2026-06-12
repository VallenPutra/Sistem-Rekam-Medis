<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    protected $table = 'tindakan';
    protected $fillable = ['kode_tindakan', 'nama_tindakan', 'tarif', 'keterangan'];

    public static function generateKode(): string
    {
        $last = static::latest()->first();
        $number = $last ? (int) substr($last->kode_tindakan, 3) + 1 : 1;
        return 'TND' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}