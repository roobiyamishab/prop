<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;

// 🔓 Admin guest routes (not logged in as admin)
Route::middleware('guest:admin')->group(function () {
    // Show login form
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])
        ->name('admin.login');

    // Handle login submit
    Route::post('/admin/login', [LoginController::class, 'login'])
        ->name('admin.login.submit');
});

// 🔐 Admin authenticated routes
Route::middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Logout
    Route::post('/admin/logout', [LoginController::class, 'logout'])
        ->name('admin.logout');
});
