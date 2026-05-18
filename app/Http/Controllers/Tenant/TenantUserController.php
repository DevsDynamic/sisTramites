<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Area;
use App\Models\Tenant\Role;
use App\Models\Tenant\TenantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TenantUserController extends Controller
{
    /* INDEX */
    public function index()
    {
        $users = TenantUser::on('tenant')
            ->with([
                'roles',
                'areas',
            ])
            ->latest()
            ->paginate(12);

        $roles = Role::orderBy('name')
            ->get();

        $areas = Area::orderBy('name')
            ->get();

        return view(
            'tenant.users.index',
            compact(
                'users',
                'roles',
                'areas'
            )
        );
    }

    /* STORE */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('tenant.users', 'email'),
            ],
            'password' => [
                'required',
                'min:8',
            ],
            'roles' => [
                'nullable',
                'array',
            ],
            'areas' => [
                'nullable',
                'array',
            ],
        ]);

        DB::connection('tenant')->beginTransaction();

        try {

            /* USER */
            $user = TenantUser::on('tenant')->create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(
                    $validated['password']
                ),
            ]);

            /* ROLES */
            $user->syncRoles(
                $validated['roles'] ?? []
            );

            /* AREAS */
            $user->areas()->sync(
                $validated['areas'] ?? []
            );

            DB::connection('tenant')->commit();

            return back()->with(
                'success',
                'Usuario creado correctamente.'
            );
        } catch (\Throwable $e) {
            DB::connection('tenant')->rollBack();
            report($e);
            return back()->with(
                'error',
                'No se pudo crear el usuario.'
            );
        }
    }

    /* UPDATE */
    public function update(
        Request $request,
        $id
    ) {

        $user = TenantUser::on('tenant')
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('tenant.users', 'email')
                    ->ignore($user->id),
            ],
            'password' => [
                'nullable',
                'min:8',
            ],
            'roles' => [
                'nullable',
                'array',
            ],
            'areas' => [
                'nullable',
                'array',
            ],
        ]);

        DB::connection('tenant')->beginTransaction();

        try {

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            /* PASSWORD */
            if ($request->filled('password')) {
                $data['password'] = Hash::make(
                    $validated['password']
                );
            }

            $user->update($data);

            /* ROLES */
            $user->syncRoles(
                $validated['roles'] ?? []
            );

            /* AREAS */
            $user->areas()->sync(
                $validated['areas'] ?? []
            );

            DB::connection('tenant')->commit();

            return back()->with(
                'success',
                'Usuario actualizado.'
            );
        } catch (\Throwable $e) {
            DB::connection('tenant')->rollBack();
            report($e);
            return back()->with(
                'error',
                'No se pudo actualizar el usuario.'
            );
        }
    }

    /* DESTROY */
    public function destroy($id)
    {
        $user = TenantUser::on('tenant')
            ->findOrFail($id);
        DB::connection('tenant')->beginTransaction();

        try {
            /* REMOVE ROLES */
            $user->syncRoles([]);
            /* REMOVE AREAS */
            $user->areas()->detach();
            $user->delete();

            DB::connection('tenant')->commit();

            return back()->with(
                'success',
                'Usuario eliminado.'
            );
        } catch (\Throwable $e) {
            DB::connection('tenant')->rollBack();
            report($e);
            return back()->with(
                'error',
                'No se pudo eliminar el usuario.'
            );
        }
    }
}
