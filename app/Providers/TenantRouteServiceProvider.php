<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class TenantRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Detectar si es una petición tenant
        $host = request()->getHost();
        $centralDomains = config('tenancy.central_domains', []);

        if (!in_array($host, $centralDomains)) {
            // Configurar sesión ANTES de que web middleware la inicie
            config([
                'session.cookie' => 'tenant_session_' . md5($host),
                'session.domain' => $host,
                'session.driver' => 'file',
            ]);
        }

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/tenant.php'));
        });
    }
}