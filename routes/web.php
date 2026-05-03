<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TenantController;

Route::domain('sistramites.com')->group(function () {

    require __DIR__ . '/auth.php'; // login SOLO central

    // Direcciona al login si no se ha autenticado
    Route::get('/', function () {
        return redirect('/login');
    });

    Route::middleware(['auth'])->group(function () {

        Route::get('/admin', [AdminController::class, 'index']);
        Route::post('/admin/tenants', [TenantController::class, 'store']);

        //Route::get('/admin/plans', [PlanController::class, 'index']);
        //Route::post('/admin/plans', [PlanController::class, 'store']);
    });
});
