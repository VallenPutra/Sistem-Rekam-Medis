<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rontgen extends Model
{
    protected $table = 'rontgen';
    protected $fillable = ['kunjungan_id', 'dokter_id', 'file_path', 'bagian_tubuh', 'hasil_analisis'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function dokter()    { return $this->belongsTo(User::class, 'dokter_id'); }
}