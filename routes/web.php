<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// App Installation Routes
Route::get('/install', [InstallController::class, 'install']);
Route::get('/callback', [InstallController::class, 'callback'])->name('callback');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
