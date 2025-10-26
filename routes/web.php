<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController; // Saya sesuaikan dengan nama controller dari contoh sebelumnya
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CompanyApprovalController;
use App\Http\Controllers\SuperAdmin\UserManagementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PetugasController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\PemakaianAirController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\KeluhanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\Admin\StatusController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- RUTE PUBLIK (Bisa diakses tanpa login) ---
Route::get('/', function () {
    return view('landing');
});

// Grup route untuk otentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
    Route::get('/register-admin', [RegisterController::class, 'showRegistrationForm'])->name('register.admin');
    Route::post('/register-admin', [RegisterController::class, 'store'])->name('register.admin.store');
});

// Route untuk logout (hanya bisa diakses jika sudah login)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// --- RUTE YANG MEMERLUKAN LOGIN ---

// Grup untuk Superadmin
Route::middleware(['auth'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('chart.data');
    // Route untuk halaman list pengajuan perusahaan yang pending
    Route::get('/companies/pending', [CompanyApprovalController::class, 'index'])->name('companies.pending');
    
    // Route untuk aksi Approve (PUT)
    Route::put('/companies/{company}/approve', [CompanyApprovalController::class, 'approve'])->name('companies.approve');
    
    // Route untuk aksi Reject (PUT)
    Route::put('/companies/{company}/reject', [CompanyApprovalController::class, 'reject'])->name('companies.reject');

    // --- CRUD Manajemen Admin ---
    // Menggunakan Resource Route untuk CRUD Admin
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

// Grup untuk Admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Route ini tidak memerlukan pengecekan status company (harus bisa diakses oleh admin yang statusnya pending)
    Route::get('/status', [StatusController::class, 'index'])->name('status');
        
    // Rute Login As (Masih menggunakan AdminController)
    Route::get('/admin/{admin}/login-as', [AdminController::class, 'loginAs'])->name('admin.login-as');

        // Route di dalam grup ini MEMERLUKAN company yang sudah 'approved'
    Route::middleware(['check.company.status'])->group(function () {
        
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        // Tambahkan route admin lainnya yang memerlukan approval di sini...
        
        // Data Petugas
        Route::get('/data-petugas', [PetugasController::class, 'index'])->name('petugas.index');
        
        // // --- CRUD DATA PETUGAS ---
        // // Menggunakan Resource Route untuk CRUD Admin
        // Route::resource('users/petugas', PetugasController::class)->names([
        // 'index' => 'admin.petugas..index',
        // 'create' => 'admin.petugas.create',
        // 'store' => 'admin.petugas.store',
        // 'show' => 'admin.petugas.show',
        // 'edit' => 'admin.petugas.edit',
        // 'update' => 'admin.petugas.update',
        // 'destroy' => 'admin.petugas.destroy',
        
        // Data Pelanggan
        Route::get('/data-pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
        
        // Pemakaian Air
        Route::get('/pemakaian-air', [PemakaianAirController::class, 'index'])->name('pemakaian.index');
        
        // Pembayaran Pelanggan
        Route::get('/pembayaran-pelanggan', [PembayaranController::class, 'index'])->name('pembayaran.index');
        
        // Keluhan Pelanggan
        Route::get('/keluhan-pelanggan', [KeluhanController::class, 'index'])->name('keluhan.index');
        
        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        
        // Informasi
        Route::get('/informasi', [InformasiController::class, 'index'])->name('informasi.index');

    });
});

Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        
    // Rute Dashboard & Chart (dari controller dummy)
    Route::get('/dashboard', [PetugasController::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [PetugasController::class, 'getChartData'])->name('chart.data');

    // Rute Sidebar Lainnya (Placeholder)
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


// ===================================
// (BARU) Grup untuk Pelanggan
// ===================================
// Asumsi Anda punya middleware 'role:pelanggan'
Route::middleware(['role:pelanggan'])->prefix('pelanggan')->name('pelanggan.')->group(function () {
    
    // Rute Dashboard & Chart (dari controller dummy)
    Route::get('/dashboard', [PelangganController::class, 'index'])->name('dashboard');
    Route::get('/chart-data', [PelangganController::class, 'getChartData'])->name('chart.data');

    // Rute Sidebar Lainnya (Placeholder)
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