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

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Peserta Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PesertaController::class, 'index'])->name('peserta.dashboard');
    Route::get('/absen', [PesertaController::class, 'absenForm'])->name('peserta.absen');
    Route::post('/absen/masuk', [PesertaController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [PesertaController::class, 'absenPulang'])->name('absen.pulang');
    
    Route::get('/izin-sakit', [PesertaController::class, 'izinSakitForm'])->name('peserta.izin_sakit');
    Route::post('/izin-sakit', [PesertaController::class, 'submitIzinSakit'])->name('peserta.submit_izin_sakit');
    
    Route::get('/riwayat', [PesertaController::class, 'riwayat'])->name('peserta.riwayat');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/peserta', [AdminController::class, 'peserta'])->name('peserta');
    Route::get('/pengajuan', [AdminController::class, 'pengajuan'])->name('pengajuan');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
});
