<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = 'obat';
    protected $fillable = [
        'kode_obat', 'nama_obat', 'satuan', 'stok', 'stok_minimum',
        'harga_beli', 'harga_jual', 'kategori', 'tanggal_kadaluarsa'
    ];
    protected $casts = ['tanggal_kadaluarsa' => 'date'];

    public function isStokRendah(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    public static function generateKodeObat(): string
    {
        $last = static::latest()->first();
        $number = $last ? (int) substr($last->kode_obat, 3) + 1 : 1;
        return 'OBT' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
