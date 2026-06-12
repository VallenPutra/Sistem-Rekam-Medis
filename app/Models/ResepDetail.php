<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResepDetail extends Model
{
    protected $table = 'resep_detail';
    protected $fillable = ['resep_id', 'obat_id', 'jumlah', 'aturan_pakai', 'harga_satuan', 'subtotal'];

    public function obat() { return $this->belongsTo(Obat::class); }
    public function resep() { return $this->belongsTo(Resep::class); }
}