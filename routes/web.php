<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CompanyApprovalController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PemakaiController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KeluhanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\HargaController;
use App\Http\Controllers\PemakaianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
});
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/register-admin', [RegisterController::class, 'showRegistrationForm'])->name('register.admin');
    Route::post('/register-admin', [RegisterController::class, 'store'])->name('register.admin.store');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('chart.data');
    Route::get('/companies/pending', [CompanyApprovalController::class, 'index'])->name('companies.pending');
    Route::put('/companies/{company}/approve', [CompanyApprovalController::class, 'approve'])->name('companies.approve');
    Route::put('/companies/{company}/reject', [CompanyApprovalController::class, 'reject'])->name('companies.reject');
    Route::resource('users/admin', UserManagementController::class)->names([
        'index' => 'users.admin.index',
        'create' => 'users.admin.create',
        'store' => 'users.admin.store',
        'show' => 'users.admin.show',
        'edit' => 'users.admin.edit',
        'update' => 'users.admin.update',
        'destroy' => 'users.admin.destroy',
    ]);
    Route::get('/management-users', [UserManagementController::class, 'index'])->name('management-users.index');
    Route::delete('/management-users/{user}', [UserManagementController::class, 'destroy'])->name('management-users.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/status', [StatusController::class, 'index'])->name('status');
    Route::get('/admin/{admin}/login-as', [AdminController::class, 'loginAs'])->name('admin.login-as');
    Route::middleware(['check.company.status'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/data-petugas', [PetugasController::class, 'index'])->name('petugas.index');
        Route::resource('petugas', PetugasController::class);
        Route::resource('pelanggan', PelangganController::class);
        Route::resource('harga', HargaController::class);
        Route::resource('pemakaian', PemakaianController::class);
        Route::get('/data-pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/pembayaran-pelanggan', [PembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('/keluhan-pelanggan', [KeluhanController::class, 'index'])->name('keluhan.index');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');

    });
});

Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [PetugasController::class, 'getChartData'])->name('chart.data');
    Route::get('/pemakaian-air', function () {
        return "Halaman Input Pemakaian Air (Petugas)";
    })->name('pemakaian.index');
    
    Route::get('/keluhan', function () {
        return "Halaman Keluhan (Petugas)";
    })->name('keluhan.index');
    
    Route::get('/informasi', function () {
        return "Halaman Informasi (Petugas)";
    })->name('informasi.index');

    Route::get('/pengaturan', function () {
        return "Halaman Pengaturan (Petugas)";
    })->name('pengaturan.index');
});
Route::middleware(['role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [PelangganController::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [PelangganController::class, 'getChartData'])->name('chart.data');
    Route::get('/tagihan', function () {
        return "Halaman Tagihan (Pelanggan)";
    })->name('tagihan.index');
    
    Route::get('/keluhan', function () {
        return "Halaman Keluhan (Pelanggan)";
    })->name('keluhan.index');
    
    Route::get('/informasi', function () {
        return "Halaman Informasi (Pelanggan)";
    })->name('informasi.index');

    Route::get('/pengaturan', function () {
        return "Halaman Pengaturan (Pelanggan)";
    })->name('pengaturan.index');
});