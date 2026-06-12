<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

//Halaman Utama → Redirect ke Login
Route::get('/', function () {
    return redirect()->route('login');
});

//Auth Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

//Protected Routes (Auth Required)
Route::middleware('auth')->group(function () {
    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //Manajemen Pasien
    Route::resource('patients', PatientController::class);

    //Rekam Medis
    Route::resource('medical-records', MedicalRecordController::class);

    //Manajemen Obat
    Route::resource('medicines', MedicineController::class)->except(['show']);
});