<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('dashboard');

Route::get('/server-info', [ServerController::class, 'serverInfo'])
    ->name('server.info');

Route::get('/health-check', [ServerController::class, 'healthCheck'])
    ->name('health.check');