<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->string('no_kunjungan')->unique();
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->integer('nomor_antrian');
            $table->enum('jenis_kunjungan', ['rawat_jalan', 'rawat_inap'])->default('rawat_jalan');
            $table->enum('status', ['menunggu', 'sedang_diperiksa', 'selesai', 'rawat_inap', 'batal'])->default('menunggu');
            $table->string('keluhan_utama')->nullable();
            // Tanda vital (diisi admin)
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->string('tekanan_darah')->nullable(); // contoh: 120/80
            $table->decimal('suhu_tubuh', 4, 1)->nullable();
            $table->integer('nadi')->nullable();
            // Rekam medis (diisi dokter)
            $table->text('anamnesis')->nullable();
            $table->text('pemeriksaan_fisik')->nullable();
            $table->string('kode_icd10')->nullable();
            $table->string('diagnosis')->nullable();
            $table->text('catatan_dokter')->nullable();
            // Biaya
            $table->decimal('jasa_dokter', 12, 2)->default(50000);
            $table->decimal('total_biaya', 12, 2)->nullable();
            $table->enum('status_bayar', ['belum', 'sudah'])->default('belum');
            $table->timestamp('bayar_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
