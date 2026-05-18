<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\TenantService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('tenant', function () {
            return new TenantService();
        });
    }

    public function boot(): void
    {
        //
    }
}
