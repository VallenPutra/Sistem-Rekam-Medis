<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RawatInap extends Model
{
    protected $table = 'rawat_inap';
    protected $fillable = [
        'kunjungan_id', 'pasien_id', 'kamar_id', 'dokter_id',
        'tanggal_masuk', 'tanggal_keluar', 'lama_hari', 'instruksi_dokter',
        'status', 'total_biaya_kamar'
    ];
    protected $casts = ['tanggal_masuk' => 'date', 'tanggal_keluar' => 'date'];

    public function pasien()    { return $this->belongsTo(Pasien::class); }
    public function kamar()     { return $this->belongsTo(Kamar::class); }
    public function dokter()    { return $this->belongsTo(User::class, 'dokter_id'); }
    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
}