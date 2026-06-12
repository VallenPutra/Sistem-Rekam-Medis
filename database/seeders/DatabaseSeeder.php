<?php

namespace Database\Seeders;

use App\Models\Medicine;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Buat Akun Admin ─────────────────────────────────────────────────
        User::create([
            'name'     => 'Admin Klinik',
            'email'    => 'admin@klinik.com',
            'password' => Hash::make('password'),
        ]);

        // ── Data Pasien Contoh ───────────────────────────────────────────────
        $patients = [
            [
                'nama'          => 'Budi Santoso',
                'tanggal_lahir' => '1985-03-15',
                'jenis_kelamin' => 'Laki-laki',
                'alamat'        => 'Jl. Merdeka No. 10, Surabaya',
                'no_telepon'    => '081234567890',
            ],
            [
                'nama'          => 'Siti Rahayu',
                'tanggal_lahir' => '1992-07-22',
                'jenis_kelamin' => 'Perempuan',
                'alamat'        => 'Jl. Diponegoro No. 5, Malang',
                'no_telepon'    => '082345678901',
            ],
            [
                'nama'          => 'Ahmad Fauzi',
                'tanggal_lahir' => '1978-11-08',
                'jenis_kelamin' => 'Laki-laki',
                'alamat'        => 'Jl. Sudirman No. 23, Sidoarjo',
                'no_telepon'    => '083456789012',
            ],
            [
                'nama'          => 'Dewi Lestari',
                'tanggal_lahir' => '1995-01-30',
                'jenis_kelamin' => 'Perempuan',
                'alamat'        => 'Jl. Imam Bonjol No. 7, Gresik',
                'no_telepon'    => '084567890123',
            ],
            [
                'nama'          => 'Eko Prasetyo',
                'tanggal_lahir' => '1988-09-14',
                'jenis_kelamin' => 'Laki-laki',
                'alamat'        => 'Jl. Gatot Subroto No. 11, Mojokerto',
                'no_telepon'    => '085678901234',
            ],
        ];

        foreach ($patients as $data) {
            Patient::create($data);
        }

        // ── Data Rekam Medis Contoh ──────────────────────────────────────────
        $records = [
            [
                'patient_id'        => 1,
                'tanggal_kunjungan' => '2024-06-01',
                'keluhan'           => 'Demam dan sakit kepala selama 2 hari',
                'diagnosa'          => 'Demam Tifoid',
                'tindakan'          => 'Pemberian antibiotik dan antipiretik',
                'catatan'           => 'Pasien disarankan istirahat dan banyak minum air',
            ],
            [
                'patient_id'        => 2,
                'tanggal_kunjungan' => '2024-06-03',
                'keluhan'           => 'Batuk dan pilek sudah 3 hari',
                'diagnosa'          => 'ISPA (Infeksi Saluran Pernapasan Atas)',
                'tindakan'          => 'Pemberian obat batuk dan vitamin C',
                'catatan'           => 'Kontrol kembali jika tidak membaik dalam 5 hari',
            ],
            [
                'patient_id'        => 1,
                'tanggal_kunjungan' => '2024-06-10',
                'keluhan'           => 'Kontrol setelah pengobatan demam tifoid',
                'diagnosa'          => 'Evaluasi pasca terapi demam tifoid',
                'tindakan'          => 'Pengecekan kondisi umum dan lanjut antibiotik',
                'catatan'           => 'Kondisi membaik, lanjutkan antibiotik 3 hari lagi',
            ],
            [
                'patient_id'        => 3,
                'tanggal_kunjungan' => '2024-06-05',
                'keluhan'           => 'Nyeri perut dan mual',
                'diagnosa'          => 'Gastritis',
                'tindakan'          => 'Pemberian antasida dan edukasi pola makan',
                'catatan'           => 'Hindari makanan pedas dan asam',
            ],
            [
                'patient_id'        => 4,
                'tanggal_kunjungan' => '2024-06-07',
                'keluhan'           => 'Gatal-gatal di kulit tangan',
                'diagnosa'          => 'Dermatitis Alergi',
                'tindakan'          => 'Pemberian antihistamin dan krim kortikosteroid',
                'catatan'           => 'Identifikasi dan hindari alergen',
            ],
        ];

        foreach ($records as $data) {
            MedicalRecord::create($data);
        }

        // ── Data Obat Contoh ─────────────────────────────────────────────────
        $medicines = [
            ['nama_obat' => 'Paracetamol 500mg', 'stok' => 500, 'satuan' => 'Tablet'],
            ['nama_obat' => 'Amoxicillin 500mg', 'stok' => 200, 'satuan' => 'Kapsul'],
            ['nama_obat' => 'Antasida DOEN', 'stok' => 150, 'satuan' => 'Tablet'],
            ['nama_obat' => 'Cetirizine 10mg', 'stok' => 300, 'satuan' => 'Tablet'],
            ['nama_obat' => 'Vitamin C 50mg', 'stok' => 1000, 'satuan' => 'Tablet'],
            ['nama_obat' => 'Ambroxol Syrup', 'stok' => 80, 'satuan' => 'Botol'],
            ['nama_obat' => 'Ibuprofen 400mg', 'stok' => 250, 'satuan' => 'Tablet'],
            ['nama_obat' => 'ORS (Oralit)', 'stok' => 400, 'satuan' => 'Sachet'],
        ];

        foreach ($medicines as $data) {
            Medicine::create($data);
        }
    }
}
