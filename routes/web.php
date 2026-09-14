<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
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

// Peserta Routes (harus login, role peserta)
Route::middleware(['auth', 'role:peserta'])->group(function () {
    Route::get('/dashboard', [PesertaController::class, 'index'])->name('peserta.dashboard');
    Route::get('/absen', [PesertaController::class, 'absenForm'])->name('peserta.absen');
    Route::post('/absen/masuk', [PesertaController::class, 'absenMasuk'])->name('absen.masuk');
    Route::post('/absen/pulang', [PesertaController::class, 'absenPulang'])->name('absen.pulang');

    // Endpoint untuk mengambil face descriptor milik user yang sedang login
    // Backend yang menentukan user berdasarkan Auth::user(), bukan dari input frontend
    Route::post('/absen/get-descriptor', [PesertaController::class, 'getDescriptor'])->name('absen.get_descriptor');

    Route::get('/izin-sakit', [PesertaController::class, 'izinSakitForm'])->name('peserta.izin_sakit');
    Route::post('/izin-sakit', [PesertaController::class, 'submitIzinSakit'])->name('peserta.submit_izin_sakit');

    Route::get('/riwayat', [PesertaController::class, 'riwayat'])->name('peserta.riwayat');
});

// Admin Routes (harus login, role admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/peserta', [AdminController::class, 'peserta'])->name('peserta');
    Route::get('/pengajuan', [AdminController::class, 'pengajuan'])->name('pengajuan');
    Route::post('/pengajuan/{id}/update', [AdminController::class, 'updatePengajuan'])->name('pengajuan.update');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');

    // Registrasi Wajah Peserta oleh Admin
    Route::get('/peserta/{id}/registrasi-wajah', [AdminController::class, 'registrasiWajahForm'])->name('peserta.registrasi_wajah');
    Route::post('/peserta/{id}/simpan-wajah', [AdminController::class, 'simpanWajah'])->name('peserta.simpan_wajah');
    Route::delete('/peserta/{id}/hapus-wajah', [AdminController::class, 'hapusWajah'])->name('peserta.hapus_wajah');
});
