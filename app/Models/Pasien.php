<?php
// ===== app/Models/Pasien.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $fillable = [
        'no_rm', 'nama', 'jenis_kelamin', 'tanggal_lahir', 'nik',
        'no_hp', 'alamat', 'golongan_darah', 'jenis_pembayaran', 'no_bpjs'
    ];

    public function kunjungan()
    {
        return $this->hasMany(Kunjungan::class);
    }

    public function rawatInap()
    {
        return $this->hasMany(RawatInap::class);
    }

    public function getUmurAttribute(): int
    {
        return \Carbon\Carbon::parse($this->tanggal_lahir)->age;
    }

    // Generate nomor RM otomatis
    public static function generateNoRM(): string
    {
        $last = static::latest()->first();
        $number = $last ? (int) substr($last->no_rm, 2) + 1 : 1;
        return 'RM' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
