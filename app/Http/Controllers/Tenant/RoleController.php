<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

use App\Models\Tenant\Role;
use App\Models\Tenant\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')
            ->latest()
            ->paginate(12);

        $permissions = Permission::orderBy('name')
            ->get()
            ->groupBy(function ($permission) {

                return explode('.', $permission->name)[0];
            });

        return view(
            'tenant.roles.index',
            compact(
                'roles',
                'permissions'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('tenant.roles', 'name'),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],
        ]);

        DB::connection('tenant')->beginTransaction();

        try {

            $role = Role::create([
                'name' => $validated['name'],
                'guard_name' => 'tenant',
            ]);

            $role->syncPermissions(
                $validated['permissions'] ?? []
            );

            DB::connection('tenant')->commit();

            return back()->with(
                'success',
                'Rol creado correctamente.'
            );
        } catch (\Throwable $e) {

            DB::connection('tenant')->rollBack();

            report($e);

            return back()->with(
                'error',
                'No se pudo crear el rol.'
            );
        }
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        // dd(
        //     tenant(),
        //     config('database.connections.tenant.database')
        // );

        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',

                Rule::unique('tenant.roles', 'name')
                    ->ignore($role->id),
            ],

            'permissions' => [
                'nullable',
                'array',
            ],
        ]);

        DB::connection('tenant')->beginTransaction();

        try {

            $role->update([
                'name' => $validated['name'],
            ]);

            $role->syncPermissions(
                $validated['permissions'] ?? []
            );

            DB::connection('tenant')->commit();

            return back()->with(
                'success',
                'Rol actualizado.'
            );
        } catch (\Throwable $e) {

            DB::connection('tenant')->rollBack();

            report($e);

            return back()->with(
                'error',
                'No se pudo actualizar el rol.'
            );
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        DB::connection('tenant')->beginTransaction();

        try {

            $role->delete();

            DB::connection('tenant')->commit();

            return back()->with(
                'success',
                'Rol eliminado.'
            );
        } catch (\Throwable $e) {

            DB::connection('tenant')->rollBack();

            report($e);

            return back()->with(
                'error',
                // 'No se pudo eliminar el rol.'
                $e->getMessage()
            );
        }
    }
}
