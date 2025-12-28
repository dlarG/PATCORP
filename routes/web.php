<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PaymentController;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Driver management
    Route::resource('drivers', DriverController::class);
    
    // File management
    Route::resource('files', FileController::class);
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    
    // Payment management
    Route::resource('payments', PaymentController::class);
    
    // Driver-specific routes
    Route::prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driverDashboard'])->name('dashboard');
        Route::get('/profile', [DriverController::class, 'profile'])->name('profile');
        Route::get('/payments', [PaymentController::class, 'driverPayments'])->name('payments');
    });

    Route::post('/drivers/{driver}/toggle-payment', [DriverController::class, 'togglePaymentStatus'])->name('drivers.togglePayment');
});