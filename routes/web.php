<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\DeploymentSimulatorController;
use App\Http\Controllers\DeploymentAnalyticsController;
use App\Http\Controllers\CiCdBuilderController;
use App\Http\Controllers\TerminalLabController;

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
| Feature 1: One-Click Deployment Simulator & Live Pipeline Visualizer
|--------------------------------------------------------------------------
*/
Route::get('/deployment/simulator', [DeploymentSimulatorController::class, 'index'])
    ->name('deployment.simulator');

Route::post('/deployment/simulator/execute', [DeploymentSimulatorController::class, 'execute'])
    ->name('deployment.simulator.execute');

/*
|--------------------------------------------------------------------------
| Feature 2: Deployment Analytics & Success Rate Trend Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/deployment/analytics', [DeploymentAnalyticsController::class, 'index'])
    ->name('deployment.analytics');

/*
|--------------------------------------------------------------------------
| Feature 3: GitHub Actions CI/CD Workflow Builder & YAML Studio
|--------------------------------------------------------------------------
*/
Route::get('/ci-cd/builder', [CiCdBuilderController::class, 'index'])
    ->name('cicd.builder');

Route::post('/ci-cd/generate', [CiCdBuilderController::class, 'generate'])
    ->name('cicd.builder.generate');

Route::post('/ci-cd/download', [CiCdBuilderController::class, 'download'])
    ->name('cicd.builder.download');

Route::post('/ci-cd/save', [CiCdBuilderController::class, 'save'])
    ->name('cicd.builder.save');

/*
|--------------------------------------------------------------------------
| Feature 7: Server SSH Terminal Simulator & Command Reference Lab
|--------------------------------------------------------------------------
*/
Route::get('/server/terminal', [TerminalLabController::class, 'index'])
    ->name('server.terminal');

Route::post('/server/terminal/execute', [TerminalLabController::class, 'execute'])
    ->name('server.terminal.execute');

/*
|--------------------------------------------------------------------------
| Deployment Management & CRUD
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
