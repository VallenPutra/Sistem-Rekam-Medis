<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KunjunganTindakan extends Model
{
    protected $table = 'kunjungan_tindakan';
    protected $fillable = ['kunjungan_id', 'tindakan_id', 'jumlah', 'tarif', 'subtotal', 'keterangan'];

    public function tindakan()
    {
        return $this->belongsTo(Tindakan::class);
    }

    public function kunjungan()
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
