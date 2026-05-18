<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',

        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/tenant.php'));
        },
    )

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            //'tenant.session' => \App\Http\Middleware\TenantSession::class,
            //'tenant.connection' => \App\Http\Middleware\InitializeTenantConnection::class,
            'tenant.status'  => \App\Http\Middleware\CheckTenantStatus::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'onboarding' => \App\Http\Middleware\EnsureTenantOnboarding::class,
            'updatelastseen' => \App\Http\Middleware\UpdateLastSeen::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 404 general central
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->view('errors.404', [], 404);
        });

        // Tenant no encontrado por dominio (el que ya te apareció)
        $exceptions->render(function (
            \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException $e,
            Request $request
        ) {
            return response()->view('errors.tenant-not-found', [
                'domain' => $request->getHost(),
            ], 404);
        });

        // Tenant no encontrado por path
        $exceptions->render(function (
            \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByPathException $e,
            Request $request
        ) {
            return response()->view('errors.tenant-not-found', [
                'domain' => $request->getHost(),
            ], 404);
        });

        // Tenancy no inicializada
        $exceptions->render(function (
            \Stancl\Tenancy\Exceptions\TenancyNotInitializedException $e,
            Request $request
        ) {
            return response()->view('errors.tenant-not-found', [
                'domain' => $request->getHost(),
            ], 503);
        });

        $exceptions->render(function (
            \Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedByRequestDataException $e,
            Request $request
        ) {
            return response()->view('errors.tenant-not-found', [
                'domain' => $request->getHost(),
            ], 404);
        });
    })->create();
