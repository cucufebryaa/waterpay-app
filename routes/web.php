<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController; // Saya sesuaikan dengan nama controller dari contoh sebelumnya
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\CompanyApprovalController;
use App\Http\Controllers\Admin\StatusController;
use App\Http\Controllers\Admin\AdminController;

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

    // Tambahkan route superadmin lainnya di sini...
});

// Grup untuk Admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Route ini tidak memerlukan pengecekan status company (harus bisa diakses oleh admin yang statusnya pending)
    Route::get('/status', [StatusController::class, 'index'])->name('status');

    // Route di dalam grup ini MEMERLUKAN company yang sudah 'approved'
    Route::middleware(['check.company.status'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        // Tambahkan route admin lainnya yang memerlukan approval di sini...
    });
});