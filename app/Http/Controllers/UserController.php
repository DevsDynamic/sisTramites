<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with([
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
            'users.index',
            compact(
                'users',
                'roles',
                'areas'
            )
        );
    }

    public function cards()
    {
        $users = User::with([
            'roles',
            'areas',
        ])
            ->latest()
            ->paginate(12);

        return view(
            'users.partials.cards',
            compact('users')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'roles' => 'nullable|array',
                'areas' => 'nullable|array',
            ],
            [
                'name.required' => 'Debe ingresar el nombre del usuario.',
                'email.required' => 'Debe ingresar el correo electrónico.',
                'email.email' => 'Debe ingresar un correo válido.',
                'email.unique' => 'Ya existe un usuario con ese correo.',
                'password.required' => 'Debe ingresar una contraseña.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ]
        );

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(
                    $validated['password']
                ),
            ]);

            $user->syncRoles(
                $validated['roles'] ?? []
            );

            $user->areas()->sync(
                $validated['areas'] ?? []
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente.'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo crear el usuario.'
            ], 500);
        }
    }

    public function update(
        Request $request,
        $id
    ) {

        $user = User::findOrFail($id);

        $validated = $request->validate(
            [
                'name' => 'required|max:255',

                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')
                        ->ignore($user->id),
                ],

                'password' => 'nullable|min:8',

                'roles' => 'nullable|array',

                'areas' => 'nullable|array',
            ],
            [
                'name.required' => 'Debe ingresar el nombre del usuario.',
                'email.required' => 'Debe ingresar el correo electrónico.',
                'email.email' => 'Debe ingresar un correo válido.',
                'email.unique' => 'Ya existe un usuario con ese correo.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            ]
        );

        DB::beginTransaction();

        try {

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if ($request->filled('password')) {

                $data['password'] =
                    Hash::make(
                        $validated['password']
                    );
            }

            $user->update($data);

            $user->syncRoles(
                $validated['roles'] ?? []
            );

            $user->areas()->sync(
                $validated['areas'] ?? []
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado correctamente.'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el usuario.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();

        try {

            $user->syncRoles([]);

            $user->areas()->detach();

            $user->update([
                'active' => false
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario desactivado correctamente.'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo desactivar el usuario.'
            ], 500);
        }
    }
}
