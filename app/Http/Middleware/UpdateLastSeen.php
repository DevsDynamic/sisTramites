<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateLastSeen
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {

            auth()->user()->update([
                'last_seen_at' => now()
            ]);
        }

        return $next($request);
    }
}
