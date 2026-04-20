<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// App Installation Routes
Route::get('/install', [InstallController::class, 'install']);
Route::get('/callback', [InstallController::class, 'callback'])->name('callback');

// Authentication Routes
Route::get('/sign-in', [AuthController::class, 'showSignIn'])->name('login');
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/sign-out', [AuthController::class, 'signOut'])->name('sign.out');
});
