<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\TenantController;

Route::domain(config('saas.central_domain'))->group(function () {
    /*
    |--------------------------------------------------------------------------
    | CENTRAL AUTH
    |--------------------------------------------------------------------------
    */

    require __DIR__ . '/auth-central.php';
    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | HOME
        |--------------------------------------------------------------------------
        */
        Route::get('/', function () {
            return redirect('/admin');
        });
        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        /*
        |--------------------------------------------------------------------------
        | PLANS
        |--------------------------------------------------------------------------
        */
        Route::resource('plans', PlanController::class);
        /*
        |--------------------------------------------------------------------------
        | TENANTS
        |--------------------------------------------------------------------------
        */
        Route::resource('tenants', TenantController::class);
    });
});
