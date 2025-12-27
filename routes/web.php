<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\KasWebController;
use App\Http\Controllers\Web\InventarisWebController;
use App\Http\Controllers\Web\SosialWebController;
use App\Http\Controllers\Web\KegiatanController;
use App\Http\Controllers\Web\HakAksesController;

// =====================
// AUTHENTICATION (WEB)
// =====================

// Halaman login
Route::get('/login', function () {return view('auth.login');})->name('login');

// Login
Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.post');
// Logout
Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');


// KAS
Route::get('/kas', [KasWebController::class,'index'])->name('kas');
Route::post('/kas', [KasWebController::class,'store'])->name('kas.store');
Route::put('/kas/{id}', [KasWebController::class,'update'])->name('kas.update');
Route::delete('/kas/{id}', [KasWebController::class,'destroy'])->name('kas.destroy');
Route::get('/kas/pdf', [KasWebController::class, 'pdf'])->name('kas.pdf');

// INVENTARIS
Route::get('/inventaris', [InventarisWebController::class, 'index'])->name('inventaris');
Route::post('/inventaris', [InventarisWebController::class, 'store'])->name('inventaris.store');
Route::put('/inventaris/{id}', [InventarisWebController::class, 'update'])->name('inventaris.update');
Route::delete('/inventaris/{id}', [InventarisWebController::class, 'destroy'])->name('inventaris.destroy');
Route::get('/inventaris/pdf', [InventarisWebController::class, 'pdf'])->name('inventaris.pdf');

// SOSIAL
Route::get('/sosial', [SosialWebController::class,'index'])->name('sosial');
Route::post('/sosial', [SosialWebController::class,'store'])->name('sosial.store');
Route::put('/sosial/{id}', [SosialWebController::class,'update'])->name('sosial.update');
Route::delete('/sosial/{id}', [SosialWebController::class,'destroy'])->name('sosial.destroy');
Route::get('/sosial/pdf', [SosialWebController::class, 'pdf'])->name('sosial.pdf');

// KEGIATAN
Route::get('/kegiatan', [KegiatanController::class, 'index'])->name('kegiatan');
Route::post('/kegiatan', [KegiatanController::class, 'store'])->name('kegiatan.store');
Route::put('/kegiatan/{id}', [KegiatanController::class, 'update']);
Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy']);

// HAK AKSES
Route::get('/hak-akses', [HakAksesController::class, 'index'])->name('hakAkses');
Route::post('/hak-akses', [HakAksesController::class, 'store'])->name('hakAkses.store');
Route::put('/hak-akses/{id}', [HakAksesController::class, 'update'])->name('hakAkses.update');
Route::delete('/hak-akses/{id}', [HakAksesController::class, 'destroy'])->name('hakAkses.destroy');
