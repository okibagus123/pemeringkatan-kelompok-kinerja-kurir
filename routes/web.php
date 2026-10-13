<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpkController;
use App\Http\Controllers\AuthController;

// ==========================================
// 1. RUTE PENGUNJUNG (Belum Login)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ==========================================
// 2. RUTE TERPROTEKSI (Wajib Login)
// ==========================================
Route::middleware('auth')->group(function () {
    
    // --- Autentikasi & Profil ---
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profil', [AuthController::class, 'updateProfile'])->name('profile.update');

    // --- Menu Halaman SPK ---
    Route::get('/', [SpkController::class, 'dashboard'])->name('dashboard');
    Route::get('/input-data', [SpkController::class, 'inputData'])->name('input.data');
    Route::get('/normalisasi', [SpkController::class, 'normalisasi'])->name('normalisasi');
    Route::get('/k-means', [SpkController::class, 'kmeans'])->name('kmeans');
    Route::get('/topsis', [SpkController::class, 'topsis'])->name('topsis');
    Route::get('/hasil-akhir', [SpkController::class, 'hasilAkhir'])->name('hasil.akhir');

    // --- Aksi Input Data Kurir ---
    Route::post('/kurir/store', [SpkController::class, 'storeKurir'])->name('kurir.store');
    Route::post('/kurir/import', [SpkController::class, 'importKurir'])->name('kurir.import');
    Route::put('/kurir/update/{id}', [SpkController::class, 'updateKurir'])->name('kurir.update');
    Route::delete('/kurir/delete/{id}', [SpkController::class, 'destroyKurir'])->name('kurir.delete');
    Route::delete('/kurir/delete-all', [SpkController::class, 'destroyAllKurir'])->name('kurir.deleteAll');

    // --- Aksi Tombol Per Tahap ---
    Route::post('/proses-normalisasi', [SpkController::class, 'runNormalisasi'])->name('normalisasi.run');
    Route::post('/batal-normalisasi', [SpkController::class, 'cancelNormalisasi'])->name('normalisasi.cancel');
    
    Route::post('/proses-kmeans', [SpkController::class, 'runKMeans'])->name('kmeans.run');
    Route::post('/proses-dbi', [SpkController::class, 'runDBI'])->name('dbi.run');
    
    Route::post('/proses-topsis', [SpkController::class, 'runTopsis'])->name('topsis.run');
});