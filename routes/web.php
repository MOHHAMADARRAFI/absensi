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


    Route::get('/izin-sakit', [PesertaController::class, 'izinSakitForm'])->name('peserta.izin_sakit');
    Route::post('/izin-sakit', [PesertaController::class, 'submitIzinSakit'])->name('peserta.submit_izin_sakit');

    Route::get('/riwayat', [PesertaController::class, 'riwayat'])->name('peserta.riwayat');
    Route::post('/riwayat/update-laporan/{id}', [PesertaController::class, 'updateLaporan'])->name('peserta.update_laporan');

    Route::get('/laporan', [PesertaController::class, 'laporan'])->name('peserta.laporan');
    Route::get('/laporan/cetak', [PesertaController::class, 'cetakLaporan'])->name('peserta.laporan.cetak');
    
    Route::get('/pengaturan', [PesertaController::class, 'pengaturan'])->name('peserta.pengaturan');
    Route::post('/pengaturan/password', [PesertaController::class, 'updatePassword'])->name('peserta.update_password');
});

// Admin Routes (harus login, role admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/peserta', [AdminController::class, 'peserta'])->name('peserta');
    Route::get('/pengajuan', [AdminController::class, 'pengajuan'])->name('pengajuan');
    
    // Edit Peserta
    Route::get('/peserta/{id}/edit', [AdminController::class, 'editPeserta'])->name('peserta.edit');
    Route::put('/peserta/{id}', [AdminController::class, 'updatePeserta'])->name('peserta.update');

    Route::post('/pengajuan/{id}/update', [AdminController::class, 'updatePengajuan'])->name('pengajuan.update');
    
    // Manajemen Kehadiran
    Route::get('/kehadiran', [AdminController::class, 'kehadiran'])->name('kehadiran');
    Route::get('/kehadiran/{id}/edit', [AdminController::class, 'editKehadiran'])->name('kehadiran.edit');
    Route::put('/kehadiran/{id}', [AdminController::class, 'updateKehadiran'])->name('kehadiran.update');

    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/cetak', [AdminController::class, 'cetakLaporan'])->name('laporan.cetak');

    Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('pengaturan');
    Route::post('/pengaturan', [AdminController::class, 'updatePengaturan'])->name('update_pengaturan');
    Route::post('/pengaturan/password', [AdminController::class, 'updatePassword'])->name('update_password');
});
