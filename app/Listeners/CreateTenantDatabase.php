<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateTenantDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event)
    {
        $tenant = $event->tenant;

        tenancy()->initialize($tenant);

        \Artisan::call('tenants:migrate', [
            '--tenants' => [$tenant->id]
        ]);

        \Artisan::call('tenants:seed', [
            '--tenants' => [$tenant->id]
        ]);
    }
}
