<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth; // Pastikan ini ditambahkan di atas

// ---- Auth ----
Route::get('/', function () {
    // 1. Cek apakah ada cookie / session login yang aktif
    if (Auth::check()) {
        $user = Auth::user();
        
        // 2. Jika dia admin, langsung terbangkan ke dashboard admin
        if ($user->isAdmin()) { // atau gunakan $user->role === 'admin' sesuai modelmu
            return redirect()->route('admin.dashboard');
        }
        
        // 3. Jika dia dokter, langsung terbangkan ke dashboard dokter
        return redirect()->route('dokter.dashboard');
    }

    // 4. Kalau benar-benar belum login, baru lempar ke halaman login biasa
    return redirect()->route('login');
});

Route::get('/login',    [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login',   [AuthController::class, 'login'])->middleware('guest');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register',[AuthController::class, 'register'])->middleware('guest');
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // Manajemen Akun (PERBAIKAN: destroy dihapus dari except karena sudah pakai Soft Delete)
    Route::resource('akun', App\Http\Controllers\Admin\AkunController::class)
        ->except(['show']);
    Route::patch('akun/{user}/toggle-aktif', [App\Http\Controllers\Admin\AkunController::class, 'toggleAktif'])
        ->name('akun.toggle-aktif');

    // Pasien (Tetap haram dihapus total)
    Route::resource('pasien', App\Http\Controllers\Admin\PasienController::class)
        ->except(['destroy']);

    // Antrian / Pendaftaran
    Route::get('/antrian',          [App\Http\Controllers\Admin\AntrianController::class, 'index'])->name('antrian.index');
    Route::get('/antrian/daftar',   [App\Http\Controllers\Admin\AntrianController::class, 'create'])->name('antrian.create');
    Route::post('/antrian',         [App\Http\Controllers\Admin\AntrianController::class, 'store'])->name('antrian.store');
    // PERBAIKAN: Tambah rute manual untuk membatalkan/menghapus antrian
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

    // Obat (PERBAIKAN: destroy dihapus dari except agar fungsi hapus obat aktif)
    Route::resource('obat', App\Http\Controllers\Admin\ObatController::class)
        ->except(['show']);

    // Kamar (PERBAIKAN: destroy dihapus dari except agar fungsi hapus kamar aktif)
    Route::resource('kamar', App\Http\Controllers\Admin\KamarController::class)
        ->except(['show']);

    // Tindakan (Master Data) (PERBAIKAN: ditambahkan 'destroy' ke dalam only)
    Route::resource('tindakan', App\Http\Controllers\Admin\TindakanController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
});

// ---- DOKTER ----
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
