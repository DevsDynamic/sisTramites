<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {

            /*
            |----------------------------------------------------------------------
            | TENANT
            |----------------------------------------------------------------------
            */

            if (tenant()) {
                return route('tenant.login');
            }

            /*
            |----------------------------------------------------------------------
            | CENTRAL
            |----------------------------------------------------------------------
            */

            return route('login');
        }

        return null;
    }
}