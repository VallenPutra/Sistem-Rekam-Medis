<?php

namespace Database\Seeders;

use App\Models\{User, Obat, Tindakan, Kamar};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@klinik.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'aktif'    => true,
        ]);

        // Dokter
        User::create([
            'name'      => 'dr. Budi Santoso',
            'email'     => 'dokter@klinik.com',
            'password'  => Hash::make('password'),
            'role'      => 'dokter',
            'no_hp'     => '081234567890',
            'aktif'     => true,
        ]);

        // Kamar rawat inap
        $kamar = [
            ['nama_kamar' => 'VIP 1',     'kelas' => 'VIP',     'tarif_per_hari' => 500000, 'kapasitas' => 1],
            ['nama_kamar' => 'VIP 2',     'kelas' => 'VIP',     'tarif_per_hari' => 500000, 'kapasitas' => 1],
            ['nama_kamar' => 'Kelas 1 A', 'kelas' => 'Kelas 1', 'tarif_per_hari' => 300000, 'kapasitas' => 2],
            ['nama_kamar' => 'Kelas 1 B', 'kelas' => 'Kelas 1', 'tarif_per_hari' => 300000, 'kapasitas' => 2],
            ['nama_kamar' => 'Kelas 2 A', 'kelas' => 'Kelas 2', 'tarif_per_hari' => 200000, 'kapasitas' => 4],
            ['nama_kamar' => 'Kelas 3 A', 'kelas' => 'Kelas 3', 'tarif_per_hari' => 100000, 'kapasitas' => 6],
        ];
        foreach ($kamar as $k) {
            Kamar::create(array_merge($k, ['status' => 'tersedia']));
        }

        // Tindakan medis
        $tindakan = [
            ['kode_tindakan' => 'TND0001', 'nama_tindakan' => 'Pasang Infus',        'tarif' => 75000],
            ['kode_tindakan' => 'TND0002', 'nama_tindakan' => 'Jahit Luka (5 Jahit)','tarif' => 150000],
            ['kode_tindakan' => 'TND0003', 'nama_tindakan' => 'Nebulisasi',           'tarif' => 100000],
            ['kode_tindakan' => 'TND0004', 'nama_tindakan' => 'Injeksi Obat',         'tarif' => 35000],
            ['kode_tindakan' => 'TND0005', 'nama_tindakan' => 'EKG',                  'tarif' => 125000],
            ['kode_tindakan' => 'TND0006', 'nama_tindakan' => 'Rontgen Dada',         'tarif' => 200000],
            ['kode_tindakan' => 'TND0007', 'nama_tindakan' => 'Ganti Perban',         'tarif' => 25000],
        ];
        foreach ($tindakan as $t) {
            Tindakan::create($t);
        }

        // Obat contoh
        $obat = [
            ['kode_obat' => 'OBT0001', 'nama_obat' => 'Paracetamol 500mg',    'satuan' => 'tablet', 'stok' => 1000, 'stok_minimum' => 100, 'harga_beli' => 500,   'harga_jual' => 1000,  'kategori' => 'Analgesik'],
            ['kode_obat' => 'OBT0002', 'nama_obat' => 'Amoxicillin 500mg',    'satuan' => 'kapsul', 'stok' => 500,  'stok_minimum' => 50,  'harga_beli' => 1500,  'harga_jual' => 3000,  'kategori' => 'Antibiotik'],
            ['kode_obat' => 'OBT0003', 'nama_obat' => 'Omeprazole 20mg',      'satuan' => 'kapsul', 'stok' => 300,  'stok_minimum' => 50,  'harga_beli' => 2000,  'harga_jual' => 4000,  'kategori' => 'Lambung'],
            ['kode_obat' => 'OBT0004', 'nama_obat' => 'Cetirizine 10mg',      'satuan' => 'tablet', 'stok' => 400,  'stok_minimum' => 50,  'harga_beli' => 1000,  'harga_jual' => 2000,  'kategori' => 'Antihistamin'],
            ['kode_obat' => 'OBT0005', 'nama_obat' => 'Ibuprofen 400mg',      'satuan' => 'tablet', 'stok' => 600,  'stok_minimum' => 100, 'harga_beli' => 1000,  'harga_jual' => 2000,  'kategori' => 'Analgesik'],
            ['kode_obat' => 'OBT0006', 'nama_obat' => 'Infus NaCl 500ml',     'satuan' => 'botol',  'stok' => 100,  'stok_minimum' => 20,  'harga_beli' => 25000, 'harga_jual' => 40000, 'kategori' => 'Infus'],
            ['kode_obat' => 'OBT0007', 'nama_obat' => 'Vitamin C 500mg',      'satuan' => 'tablet', 'stok' => 800,  'stok_minimum' => 100, 'harga_beli' => 500,   'harga_jual' => 1500,  'kategori' => 'Vitamin'],
        ];
        foreach ($obat as $o) {
            Obat::create($o);
        }
    }
}