<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterAdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;
use App\Models\User; // Pastikan baris ini ada
use App\Models\Admin; // Pastikan baris ini ada
use App\Models\Company; // Pastikan baris ini ada
use App\Models\Petugas;
use App\Models\Pelanggan; 

// Rute untuk Halaman Landing Page
Route::get('/', function () {
    return view('landing');
});

// Rute untuk login dan logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rute untuk pendaftaran admin
Route::get('/register-admin', [RegisterAdminController::class, 'showRegistrationForm'])->name('register.admin');
Route::post('/register-admin', [RegisterAdminController::class, 'store'])->name('register.admin.store');

// Grup Rute Dashboard untuk setiap peran
Route::middleware(['auth'])->group(function () {
    
    // Grup Rute Superadmin
    Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:superadmin'])->group(function () {
        Route::get('/superadmin/dashboard', function () {
            return view('superadmin.dashboard');
        })->name('superadmin.dashboard');
    });
        
        // Rute untuk halaman Approval Company
        Route::get('/superadmin/approval-company', [CompanyController::class, 'index'])->name('superadmin.approval-company');
        Route::post('/superadmin/approval-company/{company}/approve', [CompanyController::class, 'approve'])->name('superadmin.approval-company.approve');
        Route::post('/superadmin/approval-company/{company}/reject', [CompanyController::class, 'reject'])->name('superadmin.approval-company.reject');

        // Rute untuk menampilkan data admin
        Route::get('/superadmin/data-admin', [AdminController::class, 'index'])->name('superadmin.data-admin');
        
        // Rute untuk fungsionalitas CRUD dan login-as
        Route::get('/superadmin/admin/{admin}/login-as', [AdminController::class, 'loginAs'])->name('superadmin.admin.login-as');
        Route::resource('superadmin/admin', AdminController::class)->names([
            'index' => 'superadmin.admin.index',
            'create' => 'superadmin.admin.create',
            'store' => 'superadmin.admin.store',
            'edit' => 'superadmin.admin.edit',
            'update' => 'superadmin.admin.update',
            'destroy' => 'superadmin.admin.destroy',
        ]);
    });

    // Grup Rute Admin (untuk admin penyewa)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

        // Grup Rute Petugas
    Route::middleware(['role:petugas'])->group(function () {
        Route::get('/petugas/dashboard', function () {
            // Logika untuk dashboard petugas di sini
            return view('petugas.dashboard');
        })->name('petugas.dashboard');
    });
        // Rute untuk menampilkan data petugas
        Route::get('/superadmin/data-petugas', [PetugasController::class, 'index'])->name('superadmin.data-petugas');

        // Rute untuk login sebagai admin atau petugas
        Route::get('/superadmin/admin/{admin}/login-as', [AdminController::class, 'loginAs'])->name('superadmin.admin.login-as');
        Route::get('/superadmin/petugas/{petugas}/login-as', [PetugasController::class, 'loginAs'])->name('superadmin.petugas.login-as');

        // Rute Resource untuk Admin dan Petugas
        Route::resource('superadmin/admin', AdminController::class)->names([
            'index' => 'superadmin.admin.index',
            'create' => 'superadmin.admin.create',
            'store' => 'superadmin.admin.store',
            'edit' => 'superadmin.admin.edit',
            'update' => 'superadmin.admin.update',
            'destroy' => 'superadmin.admin.destroy',
        ]);
        Route::resource('superadmin/petugas', PetugasController::class)->names([
            'index' => 'superadmin.petugas.index',
            'create' => 'superadmin.petugas.create',
            'store' => 'superadmin.petugas.store',
            'edit' => 'superadmin.petugas.edit',
            'update' => 'superadmin.petugas.update',
            'destroy' => 'superadmin.petugas.destroy',
        ]);

    // Grup Rute Pelanggan (untuk pelanggan)
    Route::middleware(['role:pelanggan'])->group(function () {
        Route::get('/pelanggan/dashboard', function () {
            return view('pelanggan.dashboard');
        })->name('pelanggan.dashboard');
    });
        // Rute untuk menampilkan data pelanggan
        Route::get('/superadmin/data-pelanggan', [PelangganController::class, 'index'])->name('superadmin.data-pelanggan');

        // Rute untuk fungsionalitas login sebagai admin, petugas, dan pelanggan
        Route::get('/superadmin/admin/{admin}/login-as', [AdminController::class, 'loginAs'])->name('superadmin.admin.login-as');
        Route::get('/superadmin/petugas/{petugas}/login-as', [PetugasController::class, 'loginAs'])->name('superadmin.petugas.login-as');
        Route::get('/superadmin/pelanggan/{pelanggan}/login-as', [PelangganController::class, 'loginAs'])->name('superadmin.pelanggan.login-as');

        // Rute Resource untuk Admin, Petugas, dan Pelanggan
        Route::resource('superadmin/admin', AdminController::class)->names([
            'index' => 'superadmin.admin.index', 'create' => 'superadmin.admin.create',
            'store' => 'superadmin.admin.store', 'edit' => 'superadmin.admin.edit',
            'update' => 'superadmin.admin.update', 'destroy' => 'superadmin.admin.destroy',
        ]);
        Route::resource('superadmin/petugas', PetugasController::class)->names([
            'index' => 'superadmin.petugas.index', 'create' => 'superadmin.petugas.create',
            'store' => 'superadmin.petugas.store', 'edit' => 'superadmin.petugas.edit',
            'update' => 'superadmin.petugas.update', 'destroy' => 'superadmin.petugas.destroy',
        ]);
        Route::resource('superadmin/pelanggan', PelangganController::class)->names([
            'index' => 'superadmin.pelanggan.index', 'create' => 'superadmin.pelanggan.create',
            'store' => 'superadmin.pelanggan.store', 'edit' => 'superadmin.pelanggan.edit',
            'update' => 'superadmin.pelanggan.update', 'destroy' => 'superadmin.pelanggan.destroy',
        ]);
    });

Route::group(['middleware' => ['auth', 'role:superadmin'], 'prefix' => 'superadmin', 'as' => 'superadmin.'], function () {
    Route::get('/dashboard', function () {
        $adminCount = User::where('role', 'admin')->count();
        $petugasCount = User::where('role', 'petugas')->count();
        $pelangganCount = User::where('role', 'pelanggan')->count();
        return view('superadmin.dashboard', compact('adminCount', 'petugasCount', 'pelangganCount'));
    })->name('dashboard');

    Route::get('/superadmin/data-admin', function () {
            $admins = Admin::with('user', 'company')->get();
            return view('superadmin.data-admin', compact('admins'));
        })->name('superadmin.data-admin');
});
