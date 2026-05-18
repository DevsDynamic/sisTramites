<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Providers\TenantRouteServiceProvider::class, // ← agregar
    Stancl\Tenancy\TenancyServiceProvider::class,
];
