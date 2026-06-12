<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $fillable = [
        'no_kunjungan', 'pasien_id', 'dokter_id', 'tanggal_kunjungan',
        'nomor_antrian', 'jenis_kunjungan', 'status', 'keluhan_utama',
        'berat_badan', 'tinggi_badan', 'tekanan_darah', 'suhu_tubuh', 'nadi',
        'anamnesis', 'pemeriksaan_fisik', 'kode_icd10', 'diagnosis', 'catatan_dokter',
        'jasa_dokter',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function resep()
    {
        return $this->hasOne(Resep::class);
    }

    public function tindakan()
    {
        return $this->hasMany(KunjunganTindakan::class);
    }

    public function rontgen()
    {
        return $this->hasMany(Rontgen::class);
    }

    public function rawatInap()
    {
        return $this->hasOne(RawatInap::class);
    }

    public static function generateNoKunjungan(): string
    {
        $today = now()->format('Ymd');
        $count = static::whereDate('tanggal_kunjungan', today())->count() + 1;
        return 'KJN-' . $today . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public static function getNextAntrian(int $dokterId): int
    {
        return static::where('dokter_id', $dokterId)
            ->whereDate('tanggal_kunjungan', today())
            ->max('nomor_antrian') + 1 ?? 1;
    }

    public function hitungTotalBiaya()
{
    $biayaDokter = floatval($this->jasa_dokter ?? 0);

    $biayaObat = 0;
    if ($this->resep) {
        if ($this->resep->total_harga_obat > 0) {
            $biayaObat = floatval($this->resep->total_harga_obat);
        } elseif ($this->resep->detail) {
            $biayaObat = floatval($this->resep->detail->sum('subtotal'));
        }
    }

    $biayaTindakan = 0;
    if ($this->tindakan) {
        foreach ($this->tindakan as $item) {
            if (isset($item->harga)) {
                $biayaTindakan += floatval($item->harga);
            } elseif ($item->tindakan && isset($item->tindakan->harga)) {
                $biayaTindakan += floatval($item->tindakan->harga);
            } elseif (isset($item->tarif)) {
                $biayaTindakan += floatval($item->tarif);
            }
        }
    }

    $biayaRawatInap = 0;
    if ($this->rawatInap && $this->rawatInap->kamar) {
        $tglMasuk = \Carbon\Carbon::parse($this->rawatInap->tanggal_masuk);
        $tglKeluar = $this->rawatInap->tanggal_keluar ? \Carbon\Carbon::parse($this->rawatInap->tanggal_keluar) : now();
        $durasi = $tglMasuk->diffInDays($tglKeluar) ?: 1;
        
        $biayaRawatInap = floatval($this->rawatInap->kamar->harga_per_hari ?? 0) * $durasi;
    }

    return $biayaDokter + $biayaObat + $biayaTindakan + $biayaRawatInap;
}
}