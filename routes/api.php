<?php

use App\Http\Controllers\InstallController;

/*
|--------------------------------------------------------------------------
| Webhooks
|--------------------------------------------------------------------------
*/

// App Uninstallation Routes
Route::post('/webhook/uninstall', [InstallController::class, 'unInstall'])->name('uninstall');
