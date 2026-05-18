<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantSession
{
    public function handle(Request $request, Closure $next)
    {
        // Cookie de sesión única por tenant
        $tenantDomain = $request->getHost();
        $cookieName   = 'tenant_session_' . md5($tenantDomain);

        config([
            'session.cookie'  => $cookieName,
            'session.domain'  => $tenantDomain,
            'session.driver'  => 'file', // file para no necesitar tabla sessions por tenant
        ]);

        return $next($request);
    }
}