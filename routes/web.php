<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard admin protected
Route::middleware('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/history', [AdminController::class, 'history'])->name('history');
    Route::get('/manajemen-user', [AdminController::class, 'users'])
        ->name('manajemen.users');
    Route::post('/manajemen-user', [AdminController::class, 'storeUser'])
        ->name('manajemen.users.store');
    Route::delete('/manajemen-user/{id}', [AdminController::class, 'deleteUser'])
        ->name('manajemen.users.delete');
    Route::put('/manajemen-user/{id}', [AdminController::class, 'updateUser'])
    ->name('manajemen.users.update');
    Route::post('/manajemen-user/{id}/reset-password', [AdminController::class, 'resetPasswordUser'])
    ->name('manajemen.users.reset_password');
    Route::get('/profile', [AdminController::class, 'profile'])
    ->name('profile');
    Route::post('/profile', [AdminController::class, 'updateProfile'])
    ->name('profile.update');
    Route::post('/profile/password', [AdminController::class, 'updatePassword'])
    ->name('profile.password');
    Route::get('/data-maintenance', [AdminController::class, 'dataMaintenance'])
    ->name('data.maintenance');
    Route::put('/admin/reports/{id}', [AdminController::class, 'updateReport'])
    ->name('admin.reports.update');
    Route::delete('/admin/reports/{id}', [AdminController::class, 'deleteReport'])
    ->name('admin.reports.delete');
    Route::get('/data-barang', [AdminController::class, 'dataBarang'])->name('data.barang');
    Route::post('/data-barang/{id}/tambah-stok', [AdminController::class, 'tambahStokBarang'])
    ->name('barang.tambah.stok');
    Route::post('/data-barang/tambah', [AdminController::class, 'tambahBarangBaru'])
    ->name('barang.tambah.baru');
    Route::delete('/data-barang/{id}', [AdminController::class, 'deleteBarang'])
    ->name('barang.delete');
    Route::match(['get', 'post'], '/buat-ticket', [AdminController::class, 'buatTicket'])
    ->name('buat.ticket');
});
