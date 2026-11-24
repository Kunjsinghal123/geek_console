<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\SetupWizardController; 
use App\Http\Controllers\GoogleController; 
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GoogleSearchConsoleController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// App Installation Routes
Route::get('/install', [InstallController::class, 'install']);
Route::get('/callback', [InstallController::class, 'callback'])->name('callback');


Route::get('/setup-wizard/{store_id}', [SetupWizardController::class, 'index'])->name('setup-wizard');

Route::get('/testing', [InstallController::class, 'testing']);

// Route::get('/setup-wizard/{store_id}', function ($store_id) {
//     return "Store Id ==> " . $store_id . "<br>Step ==> " . request('step') . "<br> Message ==>" . session(['success']);
// })->name('setup-wizard');


Route::get('/google/redirect', [GoogleController::class, 'redirect']);
Route::get('/google/callback', [GoogleController::class, 'callback']);


Route::get('/google/redirect/{store_id}', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'callback'])->name('google.callback');




Route::prefix('google/search-console')->group(function () {

    Route::get('/{store_id}/verification/token', [GoogleSearchConsoleController::class, 'generateVerificationToken'])->name('google.search-console.verification.token');

    Route::get('/{store_id}/verification/verify', [GoogleSearchConsoleController::class, 'verify'])->name('google.search-console.verification.verify');

});


Route::get('/{store_id}/sitemap/submit', [GoogleSearchConsoleController::class, 'submitSitemap'])->name('google.search-console.sitemap.submit');



// Code for step 2 ferify domain 

Route::post('/generate-meta', [GoogleController::class, 'generateMeta']);
Route::post('/inject-meta', [GoogleController::class, 'injectMeta']);
Route::post('/verify-domain', [GoogleController::class, 'verifyDomain']);
Route::post('/submit-sitemap', [GoogleController::class, 'submitSitemap']);

Route::get('/test-theme-write', [GoogleController::class, 'testThemeWrite']);
Route::get('/verify-domain', [GoogleController::class, 'verifyDomain']);
Route::get('/submit-sitemap', [GoogleController::class, 'submitSitemap']);

Route::post('/google/generate-meta', [GoogleController::class, 'generateMeta'])->name('google.generate-meta');

Route::post('/google/inject-meta', [GoogleController::class, 'injectMeta'])->name('google.inject-meta');


// Code for step 2 ferify domain 

Route::get('/sign-in', [AuthController::class, 'showSignIn'])->name('login');
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/sign-out', [AuthController::class, 'signOut'])->name('sign.out');

    Route::get('/total-clicks', [DashboardController::class, 'totalClicks'])->name('total.Clicks');

});
