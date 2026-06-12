<?php
// ===== app/Models/Kamar.php =====
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $fillable = ['nama_kamar', 'kelas', 'tarif_per_hari', 'status', 'kapasitas', 'keterangan'];

    public function rawatInap()
    {
        return $this->hasMany(RawatInap::class)->where('status', 'aktif');
    }
}

// ===== app/Models/Tindakan.php =====
class Tindakan extends Model
{
    protected $fillable = ['kode_tindakan', 'nama_tindakan', 'tarif', 'keterangan'];

    public static function generateKode(): string
    {
        $last = static::latest()->first();
        $number = $last ? (int) substr($last->kode_tindakan, 3) + 1 : 1;
        return 'TND' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}

// ===== app/Models/Resep.php =====
class Resep extends Model
{
    protected $fillable = ['kunjungan_id', 'dokter_id', 'status', 'catatan', 'total_harga_obat', 'diserahkan_at'];
    protected $casts = ['diserahkan_at' => 'datetime'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function dokter()    { return $this->belongsTo(User::class, 'dokter_id'); }
    public function detail()    { return $this->hasMany(ResepDetail::class); }
}

// ===== app/Models/ResepDetail.php =====
class ResepDetail extends Model
{
    protected $fillable = ['resep_id', 'obat_id', 'jumlah', 'aturan_pakai', 'harga_satuan', 'subtotal'];

    public function obat() { return $this->belongsTo(Obat::class); }
    public function resep() { return $this->belongsTo(Resep::class); }
}

// ===== app/Models/RawatInap.php =====
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

// ===== app/Models/KunjunganTindakan.php =====
class KunjunganTindakan extends Model
{
    protected $table = 'kunjungan_tindakan';
    protected $fillable = ['kunjungan_id', 'tindakan_id', 'jumlah', 'tarif', 'subtotal', 'keterangan'];

    public function tindakan()  { return $this->belongsTo(Tindakan::class); }
    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
}

// ===== app/Models/Rontgen.php =====
class Rontgen extends Model
{
    protected $fillable = ['kunjungan_id', 'dokter_id', 'file_path', 'bagian_tubuh', 'hasil_analisis'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function dokter()    { return $this->belongsTo(User::class, 'dokter_id'); }
}
