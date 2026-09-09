<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\UlasanController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('ulasan.public.create');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin Dashboard Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [UlasanController::class, 'index'])->name('dashboard');
    Route::get('/evaluasi', [UlasanController::class, 'evaluasi'])->name('evaluasi');
    Route::post('/evaluasi/retrain', [UlasanController::class, 'retrainModel'])->name('evaluasi.retrain');
    Route::get('/ulasan', [UlasanController::class, 'ulasanList'])->name('ulasan');
    Route::post('/ulasan/sinkronisasi', [UlasanController::class, 'manualRecalculate'])->name('ulasan.sinkronisasi');
    Route::get('/ulasan/export', [UlasanController::class, 'exportCsv'])->name('ulasan.export');
    Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
    
    Route::get('/leksikon', [UlasanController::class, 'leksikonIndex'])->name('leksikon');
    Route::post('/leksikon', [UlasanController::class, 'leksikonStore'])->name('leksikon.store');
    Route::delete('/leksikon/{id}', [UlasanController::class, 'leksikonDestroy'])->name('leksikon.destroy');
    Route::post('/leksikon/simulasi', [UlasanController::class, 'simulasiNormalisasi'])->name('leksikon.simulasi');

    // Profile Management
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profil.update');
});
// Public Ulasan Routes
Route::get('/isi', [UlasanController::class, 'createPublic'])->name('ulasan.public.create');
Route::post('/kirim', [UlasanController::class, 'storePublic'])->name('ulasan.public.store');
