<?php

namespace App\Jobs\Tenancy;

use App\Models\Tenant\TenantUser;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class CreateTenantAdmin
{
    public function __construct(public TenantWithDatabase $tenant) {}

    public function handle(): void
    {
        $databaseName = $this->tenant->database()->getName();

        config(['database.connections.tenant.database' => $databaseName]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        // Cambiar conexión default para que TenantUser la use
        app('db')->setDefaultConnection('tenant');

        $user = TenantUser::create([
            'name'     => $this->tenant->business_name,
            'email'    => $this->tenant->settings['admin_email'],
            'password' => $this->tenant->settings['temp_password'],
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();
            
        $user->assignRole('Administrador');

        // Restaurar conexión default
        app('db')->setDefaultConnection('mysql');

        DB::purge('tenant');
    }
}
