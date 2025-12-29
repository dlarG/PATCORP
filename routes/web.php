<?php

use App\Http\Controllers\AnalyticsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LogController;
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
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    
    // Payment management
    Route::resource('payments', PaymentController::class);
    
    // Driver-specific routes
    Route::prefix('driver')->name('driver.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'driverDashboard'])->name('dashboard');
        Route::get('/profile', [DriverController::class, 'profile'])->name('profile');
        Route::get('/payments', [PaymentController::class, 'driverPayments'])->name('payments');
    });

    Route::resource('drivers', DriverController::class);
    Route::post('/drivers/{driver}/toggle-payment', [DriverController::class, 'togglePaymentStatus'])->name('drivers.togglePayment');


    // Logs management
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::get('/logs/export', [LogController::class, 'export'])->name('logs.export');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');


    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
});