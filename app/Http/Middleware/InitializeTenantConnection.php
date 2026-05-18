<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Facades\Tenancy;
use App\Models\Tenant;
use Spatie\Permission\PermissionRegistrar;

class InitializeTenantConnection
{
    public function handle(
        Request $request,
        Closure $next
    ) {

        /* Inicializar tenant */
        if (!tenant()) {
            $host = $request->getHost();
            $tenant = Tenant::whereHas(
                'domains',
                fn($q) =>
                $q->where('domain', $host)
            )->first();

            if ($tenant) {
                Tenancy::initialize($tenant);
            }
        }

        /* Forzar conexión tenant */

        if (tenant()) {
            $dbName = tenant()->database()->getName();
            config([
                'database.connections.tenant.database'
                => $dbName
            ]);

            DB::purge('tenant');
            DB::reconnect('tenant');
            DB::setDefaultConnection('tenant');

            /* Limpiar cache Spatie */

            app(PermissionRegistrar::class)
                ->forgetCachedPermissions();
        }

        return $next($request);
    }
}
