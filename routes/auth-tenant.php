<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Tenant\AuthenticatedSessionController;

Route::middleware('guest:tenant')->group(function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('tenant.login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:tenant')->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('tenant.logout');
});
