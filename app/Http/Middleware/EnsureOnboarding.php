<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOnboarding
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $settings = setting();

        if (! $settings) {
            return redirect()
                ->route('onboarding.welcome');
        }

        /* YA COMPLETÓ */
        if ($settings->onboarding_completed) {
            if (
                $request->routeIs('onboarding.*')
            ) {
                return redirect()
                    ->route('dashboard');
            }

            return $next($request);
        }

        /* NO COMPLETÓ */
        if (
            ! $request->routeIs('onboarding.*')
        ) {
            return redirect()
                ->route('onboarding.welcome');
        }

        return $next($request);
    }
}
