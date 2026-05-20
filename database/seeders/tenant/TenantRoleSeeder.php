<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use App\Models\Tenant\Role;
use App\Models\Tenant\Permission;

class TenantRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            'dashboard.view',

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            'areas.view',
            'areas.create',
            'areas.edit',
            'areas.delete',

            'document-types.view',
            'document-types.create',
            'document-types.edit',
            'document-types.delete',

            'documents.view',
            'documents.create',
            'documents.edit',
            'documents.delete',
            'documents.sign',

            'document-series.view',
            'document-series.create',
            'document-series.edit',
            'document-series.delete',

            'signature.view',
            'signature.create',
            'signature.edit',
            'signature.delete',

            'flows.view',
            'flows.create',
            'flows.edit',
            'flows.delete',

            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'tenant',
            ]);
        }

        $admin = Role::firstOrCreate([
            'name' => 'Administrador',
            'guard_name' => 'tenant',
        ]);

        $admin->syncPermissions(
            Permission::all()
        );
    }
}
