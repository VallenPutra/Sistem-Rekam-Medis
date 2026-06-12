<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Auth & Pengalihan Awal
Route::get('/', function () {
    // 1. Cek apakah ada cookie / session login yang aktif
    if (Auth::check()) {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('dokter.dashboard');
    }

    return redirect()->route('login');
});

// ---- Rute Otentikasi Umum (GUEST) ----
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // PERBAIKAN: Menambahkan Fitur Lupa Password Resmi ke Gmail
    Route::get('/forgot-password',  [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password',        [AuthController::class, 'updatePassword'])->name('password.update');
});

// Logout (Harus Sudah Login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


// ---- GROUP ADMIN ----
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('akun', App\Http\Controllers\Admin\AkunController::class)
        ->except(['show']);
    Route::patch('akun/{user}/toggle-aktif', [App\Http\Controllers\Admin\AkunController::class, 'toggleAktif'])
        ->name('akun.toggle-aktif');

    Route::resource('pasien', App\Http\Controllers\Admin\PasienController::class)
        ->except(['destroy']);

    // Antrian / Pendaftaran
    Route::get('/antrian',          [App\Http\Controllers\Admin\AntrianController::class, 'index'])->name('antrian.index');
    Route::get('/antrian/daftar',   [App\Http\Controllers\Admin\AntrianController::class, 'create'])->name('antrian.create');
    Route::post('/antrian',         [App\Http\Controllers\Admin\AntrianController::class, 'store'])->name('antrian.store');
    Route::delete('/antrian/{kunjungan}', [App\Http\Controllers\Admin\AntrianController::class, 'destroy'])->name('antrian.destroy');

    // Resep (Apotek)
    Route::get('/resep',            [App\Http\Controllers\Admin\ResepAdminController::class, 'index'])->name('resep.index');
    Route::get('/resep/{resep}',    [App\Http\Controllers\Admin\ResepAdminController::class, 'show'])->name('resep.show');
    Route::patch('/resep/{resep}/proses', [App\Http\Controllers\Admin\ResepAdminController::class, 'proses'])->name('resep.proses');

    // Kasir
    Route::get('/kasir',                [App\Http\Controllers\Admin\KasirController::class, 'index'])->name('kasir.index');
    Route::get('/kasir/{kunjungan}',    [App\Http\Controllers\Admin\KasirController::class, 'show'])->name('kasir.show');
    Route::post('/kasir/{kunjungan}/bayar', [App\Http\Controllers\Admin\KasirController::class, 'bayar'])->name('kasir.bayar');
    Route::get('/kasir/{kunjungan}/nota', [App\Http\Controllers\Admin\KasirController::class, 'nota'])->name('kasir.nota');

    // Nota
    Route::get('/nota', [App\Http\Controllers\Admin\NotaController::class, 'index'])->name('nota.index');
    Route::get('/nota/{kunjungan}', [App\Http\Controllers\Admin\NotaController::class, 'show'])->name('nota.show');

    // Obat
    Route::resource('obat', App\Http\Controllers\Admin\ObatController::class)
        ->except(['show']);

    // Kamar
    Route::resource('kamar', App\Http\Controllers\Admin\KamarController::class)
        ->except(['show']);

    // Tindakan
    Route::resource('tindakan', App\Http\Controllers\Admin\TindakanController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});


// ---- GROUP DOKTER ----
Route::prefix('dokter')->name('dokter.')->middleware(['auth', 'role:dokter'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Dokter\DashboardController::class, 'index'])
        ->name('dashboard');

    // Rekam Medis
    Route::get('/periksa/{kunjungan}',  [App\Http\Controllers\Dokter\RekamMedisController::class, 'periksa'])->name('periksa');
    Route::post('/periksa/{kunjungan}', [App\Http\Controllers\Dokter\RekamMedisController::class, 'simpan'])->name('periksa.simpan');
    Route::get('/riwayat/{pasien}',     [App\Http\Controllers\Dokter\RekamMedisController::class, 'riwayat'])->name('riwayat');

    // E-Resep
    Route::post('/resep/{kunjungan}', [App\Http\Controllers\Dokter\ResepDokterController::class, 'store'])->name('resep.store');

    // Tindakan
    Route::post('/tindakan/{kunjungan}',        [App\Http\Controllers\Dokter\TindakanDokterController::class, 'store'])->name('tindakan.store');
    Route::delete('/tindakan-item/{tindakan}',  [App\Http\Controllers\Dokter\TindakanDokterController::class, 'destroy'])->name('tindakan.destroy');

    // Rontgen
    Route::post('/rontgen/{kunjungan}', [App\Http\Controllers\Dokter\RontgenController::class, 'store'])->name('rontgen.store');

    // Rawat Inap
    Route::get('/rawat-inap/{kunjungan}/admisi',    [App\Http\Controllers\Dokter\RawatInapDokterController::class, 'pilihKamar'])->name('rawat_inap.admisi');
    Route::post('/rawat-inap/{kunjungan}/admisi',   [App\Http\Controllers\Dokter\RawatInapDokterController::class, 'admisi'])->name('rawat_inap.admisi.store');
    Route::post('/rawat-inap/{rawatInap}/keluar',   [App\Http\Controllers\Dokter\RawatInapDokterController::class, 'keluarkan'])->name('rawat_inap.keluar');
});