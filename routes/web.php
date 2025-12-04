<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Web\KasWebController;
use App\Http\Controllers\Web\InventarisWebController;
use App\Http\Controllers\Web\SosialWebController;

// =====================
// AUTHENTICATION (WEB)
// =====================

// Halaman login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Proses login
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');

// Logout
Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

// =====================
// DASHBOARD & MENU
// =====================

// Dashboard
Route::get('/dashboard', fn() => view('pages.dashboard', ['pageTitle'=>'Selamat Datang']))->name('dashboard');

// Halaman kegiatan
Route::get('/kegiatan', fn() => view('pages.kegiatan', ['pageTitle'=>'Kegiatan']))->name('kegiatan');

// Halaman hak akses
Route::get('/hakAkses', fn() => view('pages.hakAkses', ['pageTitle'=>'Hak Akses']))->name('hakAkses');

// =====================
// KAS
// =====================

Route::get('/kas', [KasWebController::class,'index'])->name('kas');
Route::post('/kas', [KasWebController::class,'store'])->name('kas.store');
Route::put('/kas/{id}', [KasWebController::class,'update'])->name('kas.update');
Route::delete('/kas/{id}', [KasWebController::class,'destroy'])->name('kas.destroy');

// =====================
// INVENTARIS
// =====================

Route::get('/inventaris', [InventarisWebController::class, 'index'])->name('inventaris');
Route::post('/inventaris', [InventarisWebController::class, 'store'])->name('inventaris.store');
Route::put('/inventaris/{id}', [InventarisWebController::class, 'update'])->name('inventaris.update');
Route::delete('/inventaris/{id}', [InventarisWebController::class, 'destroy'])->name('inventaris.destroy');

// =====================
// SOSIAL
// =====================

Route::get('/sosial', [SosialWebController::class,'index'])->name('sosial');
Route::post('/sosial', [SosialWebController::class,'store'])->name('sosial.store');
Route::put('/sosial/{id}', [SosialWebController::class,'update'])->name('sosial.update');
Route::delete('/sosial/{id}', [SosialWebController::class,'destroy'])->name('sosial.destroy');
