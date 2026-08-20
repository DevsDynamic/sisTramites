<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermission($request, 'users.view');

        return view('users.index', [
            'users' => $this->getItems($request),
            'roles' => Role::orderBy('name')->get(),
            'areas' => Area::orderBy('name')->get(),
            'canManageAdmins' => $this->canManageAdmins($request),
            'isSystemOwner' => $this->isSystemOwner($request),
        ]);
    }

    public function cards(Request $request)
    {
        $this->ensurePermission($request, 'users.view');

        return view('users.partials.results', [
            'users' => $this->getItems($request),
            'canManageAdmins' => $this->canManageAdmins($request),
            'isSystemOwner' => $this->isSystemOwner($request),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission($request, 'users.create');

        $data = $this->validateData($request);
        $this->ensureAdminRoleAssignment($request, $data['roles'] ?? []);

        DB::transaction(function () use ($data, $request) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'active' => $request->boolean('active'),
            ]);

            $user->syncRoles($data['roles'] ?? []);
            $user->areas()->sync($data['areas'] ?? []);
        });

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado correctamente.',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->ensurePermission($request, 'users.edit');
        $this->ensureCanManageUser($request, $user);

        $data = $this->validateData($request, $user);
        $this->ensureAdminRoleAssignment($request, $data['roles'] ?? []);
        $this->ensureSelfRoleIntegrity($request, $user, $data['roles'] ?? []);

        if ($user->active && ! $request->boolean('active')) {
            $this->ensureNotCurrentUser($request, $user);
            $this->ensureNotLastActiveAdministrator($user);
        }

        DB::transaction(function () use ($data, $request, $user) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'] ?? $user->password,
                'active' => $request->boolean('active'),
            ]);

            $user->syncRoles($data['roles'] ?? []);
            $user->areas()->sync($data['areas'] ?? []);
        });

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.',
        ]);
    }

    public function destroy(Request $request, User $user)
    {
        $this->ensurePermission($request, 'users.delete');
        $this->ensureCanManageUser($request, $user);
        $this->ensureNotCurrentUser($request, $user);
        $this->ensureNotLastActiveAdministrator($user);

        if (! $user->canDelete()) {
            return response()->json([
                'message' => 'No se puede eliminar un usuario con registros relacionados.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente.',
        ]);
    }

    public function active(Request $request, User $user)
    {
        $this->ensurePermission($request, 'users.edit');
        $this->ensureCanManageUser($request, $user);

        if ($user->active) {
            $this->ensureNotCurrentUser($request, $user);
            $this->ensureNotLastActiveAdministrator($user);
        }

        $user->update(['active' => ! $user->active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del usuario actualizado correctamente.',
        ]);
    }

    private function getItems(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $active = $request->input('active', '1');
        $role = $request->input('role');
        $areaId = $request->input('area_id');

        if (! in_array($active, ['0', '1', 'all'], true)) {
            $active = '1';
        }

        return User::query()
            ->with(['roles', 'areas'])
            ->when(
                ! $this->isSystemOwner($request),
                fn($query) => $query->where('is_system_owner', false)
            )
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(filled($role), fn($query) => $query->role($role))
            ->when(filled($areaId), function ($query) use ($areaId) {
                $query->whereHas('areas', fn($areaQuery) => $areaQuery->whereKey($areaId));
            })
            ->when($active !== 'all', fn($query) => $query->where('active', $active === '1'))
            ->latest('id')
            ->paginate(config('crud.pagination', 12))
            ->withQueryString();
    }

    private function validateData(Request $request, ?User $user = null): array
    {
        $passwordRule = $user ? ['nullable', 'min:8'] : ['required', 'min:8'];

        return $request->validate([
            'name' => ['required', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user),
            ],
            'password' => $passwordRule,
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,name'],
            'areas' => ['nullable', 'array'],
            'areas.*' => ['exists:areas,id'],
            'active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Debe ingresar el nombre del usuario.',
            'email.required' => 'Debe ingresar el correo electrónico.',
            'email.email' => 'Debe ingresar un correo válido.',
            'email.unique' => 'Ya existe un usuario con ese correo.',
            'password.required' => 'Debe ingresar una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);
    }

    private function canManageAdmins(Request $request): bool
    {
        return $request->user()->can('users.manage-admins');
    }

    private function isSystemOwner(Request $request): bool
    {
        return $request->user()->isSystemOwner();
    }

    private function ensurePermission(Request $request, string $permission): void
    {
        abort_unless(
            $this->isSystemOwner($request)
                || $request->user()->can($permission),
            403
        );
    }

    private function ensureCanManageUser(Request $request, User $user): void
    {
        abort_unless(
            ! $user->isSystemOwner() || $this->isSystemOwner($request),
            403
        );

        abort_unless(
            $this->isSystemOwner($request)
                || ! $user->hasRole('Administrador')
                || $this->canManageAdmins($request),
            403
        );
    }

    private function ensureAdminRoleAssignment(Request $request, array $roles): void
    {
        if (in_array('Administrador', $roles, true) && ! $this->canManageAdmins($request)) {
            throw ValidationException::withMessages([
                'roles' => 'No tiene permiso para asignar el rol Administrador.',
            ]);
        }
    }

    private function ensureSelfRoleIntegrity(
        Request $request,
        User $user,
        array $roles
    ): void {
        if ($user->isNot($request->user())) {
            return;
        }

        $currentRoles = $user->roles->pluck('name')->sort()->values()->all();
        $newRoles = collect($roles)->sort()->values()->all();

        if ($currentRoles !== $newRoles) {
            throw ValidationException::withMessages([
                'roles' => 'No puede modificar sus propios roles.',
            ]);
        }
    }

    private function ensureNotCurrentUser(Request $request, User $user): void
    {
        if ($user->is($request->user())) {
            throw ValidationException::withMessages([
                'user' => 'No puede desactivar ni eliminar su propia cuenta.',
            ]);
        }
    }

    private function ensureNotLastActiveAdministrator(User $user): void
    {
        if (! $user->hasRole('Administrador')) {
            return;
        }

        $activeAdministrators = User::query()
            ->where('active', true)
            ->role('Administrador')
            ->count();

        if ($activeAdministrators <= 1) {
            throw ValidationException::withMessages([
                'user' => 'No se puede desactivar al último administrador activo.',
            ]);
        }
    }
}
