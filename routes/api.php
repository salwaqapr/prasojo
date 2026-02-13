<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\KasController;
use App\Http\Controllers\API\InventarisController;
use App\Http\Controllers\API\SosialController;
use App\Http\Controllers\API\KegiatanController;
use App\Http\Controllers\API\HakAksesController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\IuranMemberController;
use App\Http\Controllers\API\IuranPaymentController;

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index']);

Route::get('/kas', [KasController::class, 'index']);
Route::post('/kas', [KasController::class, 'store']);
Route::put('/kas/{id}', [KasController::class, 'update']);
Route::delete('/kas/{id}', [KasController::class, 'destroy']);
Route::get('/kas/pdf', [KasController::class, 'pdf']);

Route::get('/inventaris', [InventarisController::class, 'index']);
Route::post('/inventaris', [InventarisController::class, 'store']);
Route::put('/inventaris/{id}', [InventarisController::class, 'update']);
Route::delete('/inventaris/{id}', [InventarisController::class, 'destroy']);
Route::get('/inventaris/pdf', [InventarisController::class, 'pdf']);

Route::get('/sosial', [SosialController::class, 'index']);
Route::post('/sosial', [SosialController::class, 'store']);
Route::put('/sosial/{id}', [SosialController::class, 'update']);
Route::delete('/sosial/{id}', [SosialController::class, 'destroy']);
Route::get('/sosial/pdf', [SosialController::class, 'pdf']);

Route::get('/kegiatan', [KegiatanController::class, 'index']);
Route::post('/kegiatan', [KegiatanController::class, 'store']);
Route::put('/kegiatan/{id}', [KegiatanController::class, 'update']);
Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy']);

Route::get('/hakAkses', [HakAksesController::class, 'index']);
Route::post('/hakAkses', [HakAksesController::class, 'store']);
Route::put('/hakAkses/{id}', [HakAksesController::class, 'update']);
Route::delete('/hakAkses/{id}', [HakAksesController::class, 'destroy']);

// Anggota iuran
Route::get('/iuran-members', [IuranMemberController::class, 'index']);
Route::post('/iuran-members', [IuranMemberController::class, 'store']);
Route::put('/iuran-members/{id}', [IuranMemberController::class, 'update']);
Route::delete('/iuran-members/{id}', [IuranMemberController::class, 'destroy']);

// Iuran per anggota
Route::get('/iuran-members/{memberId}/payments', [IuranPaymentController::class, 'listByMember']);
Route::post('/iuran-members/{memberId}/payments', [IuranPaymentController::class, 'store']);

Route::get('/iuran-members/{memberId}/summary', [IuranPaymentController::class, 'summaryByMember']);

Route::put('/iuran-payments/{paymentId}', [IuranPaymentController::class, 'update']);
Route::delete('/iuran-payments/{paymentId}', [IuranPaymentController::class, 'destroy']);
