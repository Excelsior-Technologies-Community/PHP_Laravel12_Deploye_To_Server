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

/*
|--------------------------------------------------------------------------
| Deployment Module
|--------------------------------------------------------------------------
*/

Route::get('/deployments', [ServerController::class, 'deploymentList'])
    ->name('deployments.index');

Route::get('/deployments/create', [ServerController::class, 'createDeployment'])
    ->name('deployments.create');

Route::post('/deployments/store', [ServerController::class, 'storeDeployment'])
    ->name('deployments.store');

Route::get('/deployments/{deployment}/edit', [ServerController::class, 'editDeployment'])
    ->name('deployments.edit');

Route::put('/deployments/{deployment}', [ServerController::class, 'updateDeployment'])
    ->name('deployments.update');

Route::delete('/deployments/{deployment}', [ServerController::class, 'deleteDeployment'])
    ->name('deployments.delete');

Route::get('/deployments/export/csv', [ServerController::class, 'exportCsv'])
    ->name('deployments.export');
