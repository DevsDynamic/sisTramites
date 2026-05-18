<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $tenant = tenant();

        if (!$tenant) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Suspendido
        |--------------------------------------------------------------------------
        */

        if ($tenant->status === 'suspended') {

            return response()->view(
                'tenant.suspended'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Expirado
        |--------------------------------------------------------------------------
        */

        if (
            $tenant->expires_at &&
            now()->greaterThan($tenant->expires_at)
        ) {

            return response()->view(
                'tenant.expired'
            );
        }

        return $next($request);
    }
}