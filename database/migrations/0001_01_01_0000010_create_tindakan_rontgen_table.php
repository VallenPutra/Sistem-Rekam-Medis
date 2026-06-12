<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tindakan yang dilakukan pada kunjungan
        Schema::create('kunjungan_tindakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungan')->onDelete('cascade');
            $table->foreignId('tindakan_id')->constrained('tindakan')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->decimal('tarif', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Foto rontgen
        Schema::create('rontgen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kunjungan_id')->constrained('kunjungan')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->string('bagian_tubuh')->nullable(); // dada, perut, tangan, dll
            $table->text('hasil_analisis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rontgen');
        Schema::dropIfExists('kunjungan_tindakan');
    }
};
