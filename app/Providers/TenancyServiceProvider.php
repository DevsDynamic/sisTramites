<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use App\Jobs\Tenancy\CreateTenantAdmin;
use App\Jobs\Tenancy\MigrateTenantDatabase;
use App\Jobs\Tenancy\SeedTenantDatabase;

class TenancyServiceProvider extends ServiceProvider
{
    public array $events = [
        Events\TenantCreated::class => [
            Jobs\CreateDatabase::class,
            MigrateTenantDatabase::class,
            SeedTenantDatabase::class,
            CreateTenantAdmin::class,
        ],

        Events\TenantDeleted::class => [
            Jobs\DeleteDatabase::class,

        ],
    ];

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tenant pipelines
        |--------------------------------------------------------------------------
        */

        foreach ($this->events as $event => $listeners) {

            if (empty($listeners)) {
                continue;
            }

            $pipeline = JobPipeline::make($listeners)
                ->send(fn($event) => $event->tenant)
                ->toListener();

            Event::listen($event, $pipeline);
        }

        /*
        |--------------------------------------------------------------------------
        | When tenancy initializes
        |--------------------------------------------------------------------------
        */

        Event::listen(
            Events\TenancyInitialized::class,
            function (Events\TenancyInitialized $event) {

                $database = $event
                    ->tenancy
                    ->tenant
                    ->database()
                    ->getName();

                config([
                    'database.connections.tenant.database' => $database,
                ]);

                DB::purge('tenant');

                DB::reconnect('tenant');

                // IMPORTANTE
                DB::setDefaultConnection('tenant');

                app(
                    \Spatie\Permission\PermissionRegistrar::class
                )->forgetCachedPermissions();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | When tenancy ends
        |--------------------------------------------------------------------------
        */

        Event::listen(
            Events\TenancyEnded::class,
            function () {

                DB::purge('tenant');

                DB::setDefaultConnection('mysql');
            }
        );
    }
}
