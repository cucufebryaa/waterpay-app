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
use App\Http\Controllers\Pelanggan\PelangganTransaction;
use App\Http\Controllers\Pelanggan\DashboardPelanggan;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\HargaController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\Petugas\DashboardPetugas;
use App\Http\Controllers\Petugas\taskMaintenanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
// route untuk dashboard landing
Route::get('/', function () {
    return view('landing');
});

// route untuk login dan register
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::middleware('guest')->group(function () {
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/register-admin', [RegisterController::class, 'showRegistrationForm'])->name('register.admin');
    Route::post('/register-admin', [RegisterController::class, 'store'])->name('register.admin.store');
});

// route untuk logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// route untuk menu superadmiin
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

// route untuk menu admin
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
        Route::resource('informasi', InformasiController::class);
        Route::resource('keluhan', KeluhanController::class);
        Route::resource('pembayaran', PembayaranController::class);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });
});

// route untuk menu petugas
Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('dashboard', [DashboardPetugas::class,'index'])->name('dashboard');
    Route::resource('maintenance', taskMaintenanceController::class);
    Route::patch('/maintenance/{id}/start', [taskMaintenanceController::class, 'startProgress'])->name('maintenance.start');
});

// route untuk menu pelanggan
Route::middleware(['role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    Route::get('/dashboard', [DashboardPelanggan::class, 'index'])->name('dashboard');
    Route::get('/profile', [PelangganTransaction::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [PelangganTransaction::class, 'update'])->name('profile.update');
    Route::resource('transaction',PelangganTransaction::class);
});