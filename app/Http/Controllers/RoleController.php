<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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
            'roles.index',
            compact(
                'roles',
                'permissions'
            )
        );
    }

    public function cards()
    {
        $roles = Role::with('permissions')
            ->latest()
            ->paginate(12);

        return view(
            'roles.partials.cards',
            compact('roles')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('roles', 'name'),
                ],

                'permissions' => [
                    'nullable',
                    'array',
                ],
            ],
            [
                'name.required' =>
                'Debe ingresar el nombre del rol.',

                'name.unique' =>
                'Ya existe un rol con ese nombre.',
            ]
        );

        DB::beginTransaction();

        try {

            $role = Role::create([
                'name'       => $validated['name'],
                'guard_name' => 'web',
            ]);

            $role->syncPermissions(
                $validated['permissions'] ?? []
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rol creado correctamente.',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el rol.',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request->validate(
            [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('roles', 'name')
                        ->ignore($role->id),
                ],

                'permissions' => [
                    'nullable',
                    'array',
                ],
            ],
            [
                'name.required' =>
                'Debe ingresar el nombre del rol.',

                'name.unique' =>
                'Ya existe un rol con ese nombre.',
            ]
        );

        DB::beginTransaction();

        try {

            $role->update([
                'name' => $validated['name'],
            ]);

            $role->syncPermissions(
                $validated['permissions'] ?? []
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rol actualizado correctamente.',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el rol.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        DB::beginTransaction();

        try {

            $role->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado correctamente.',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar el rol.',
            ], 500);
        }
    }
}
