<?php

namespace App\Jobs\Tenancy;

use Illuminate\Support\Facades\DB;
use Database\Seeders\Tenant\TenantRoleSeeder;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class SeedTenantDatabase
{
    public function __construct(public TenantWithDatabase $tenant) {}


    public function handle(): void
    {
        $databaseName = $this->tenant
            ->database()
            ->getName();

        /*
        |--------------------------------------------------------------------------
        | CONECTAR TENANT
        |--------------------------------------------------------------------------
        */

        config([
            'database.connections.tenant.database'
            => $databaseName
        ]);

        DB::purge('tenant');

        DB::reconnect('tenant');

        app('db')
            ->setDefaultConnection('tenant');

        /*
        |--------------------------------------------------------------------------
        | EJECUTAR SEEDER
        |--------------------------------------------------------------------------
        */

        $seeder = new TenantRoleSeeder();

        $seeder->run();

        /*
        |--------------------------------------------------------------------------
        | RESTORE
        |--------------------------------------------------------------------------
        */

        app('db')
            ->setDefaultConnection('mysql');

        DB::purge('tenant');
    }
}
