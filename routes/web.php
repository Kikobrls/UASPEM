<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\UserController;

// Route untuk guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk Admin dan Manager
    Route::middleware('check.role:admin,manager')->group(function () {
        // Manajemen Jabatan
        Route::resource('jabatan', JabatanController::class);
        
        // Manajemen Karyawan
        Route::resource('karyawan', KaryawanController::class);
        
        // Manajemen Gaji
        Route::get('gaji', [GajiController::class, 'index'])->name('gaji.index');
        Route::get('gaji/create', [GajiController::class, 'create'])->name('gaji.create');
        Route::post('gaji', [GajiController::class, 'store'])->name('gaji.store');
        Route::get('gaji/{gaji}', [GajiController::class, 'show'])->name('gaji.show');
        Route::get('gaji/{gaji}/edit', [GajiController::class, 'edit'])->name('gaji.edit');
        Route::put('gaji/{gaji}', [GajiController::class, 'update'])->name('gaji.update');
        Route::delete('gaji/{gaji}', [GajiController::class, 'destroy'])->name('gaji.destroy');
        
        // Approve gaji
        Route::post('gaji/{gaji}/approve', [GajiController::class, 'approve'])->name('gaji.approve');
    });

    // Route khusus untuk Admin
    Route::middleware('check.role:admin')->group(function () {
        // Pembayaran gaji
        Route::post('gaji/{gaji}/pay', [GajiController::class, 'pay'])->name('gaji.pay');
        
        // Manajemen User
        Route::resource('users', UserController::class);
    });

    // Route untuk semua role (termasuk karyawan)
    Route::get('/slip-gaji', [GajiController::class, 'mySlip'])->name('gaji.my-slip');
    Route::get('/slip-gaji/{gaji}', [GajiController::class, 'show'])->name('slip.show');
});



