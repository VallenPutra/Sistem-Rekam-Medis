<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    protected $table = 'resep';
    protected $fillable = ['kunjungan_id', 'dokter_id', 'status', 'catatan', 'total_harga_obat', 'diserahkan_at'];
    protected $casts = ['diserahkan_at' => 'datetime'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function dokter()    { return $this->belongsTo(User::class, 'dokter_id'); }
    public function detail()    { return $this->hasMany(ResepDetail::class); }
}