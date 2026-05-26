<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class TenantService
{
    /**
     * Obtener tenant activo
     */
    public static function id(): ?int
    {
        return Auth::user()?->tenant_id;
    }

    /**
     * Saber si hay tenant activo
     */
    public static function active(): bool
    {
        return self::id() !== null;
    }

    function tenant_id()
    {
        return tenant()?->id;
    }
}
