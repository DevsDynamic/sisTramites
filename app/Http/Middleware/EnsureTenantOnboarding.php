<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantOnboarding
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $tenant = tenant();

        /*
        |--------------------------------------------------------------------------
        | SI YA TERMINÓ ONBOARDING
        |--------------------------------------------------------------------------
        */

        if ($tenant->onboarding_completed) {

            /*
            |--------------------------------------------------------------------------
            | SI QUIERE VOLVER AL ONBOARDING
            |--------------------------------------------------------------------------
            */

            if ($request->is('onboarding*')) {

                return redirect()
                    ->route('tenant.dashboard');
            }

            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | SI NO TERMINÓ ONBOARDING
        |--------------------------------------------------------------------------
        */

        if (!$request->is('onboarding*')) {

            return redirect()
                ->route('tenant.onboarding.welcome');
        }

        return $next($request);
    }
}