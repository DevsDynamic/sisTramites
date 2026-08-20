<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermission($request, 'roles.view');

        return view('roles.index', [
            'roles' => $this->getItems($request),
            'permissions' => $this->permissionsByModule($request),
            'modules' => Permission::query()
                ->orderBy('name')
                ->get()
                ->map(fn($permission) => explode('.', $permission->name)[0])
                ->unique()
                ->values(),
            'canManageSystem' => $this->canManageSystem($request),
        ]);
    }

    public function cards(Request $request)
    {
        $this->ensurePermission($request, 'roles.view');

        return view('roles.partials.results', [
            'roles' => $this->getItems($request),
            'canManageSystem' => $this->canManageSystem($request),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensurePermission($request, 'roles.create');

        $data = $this->validateData($request);
        $this->ensureAssignablePermissions($request, $data['permissions'] ?? []);

        DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'guard_name' => 'web',
            ]);

            $role->syncPermissions($data['permissions'] ?? []);
        });

        return response()->json([
            'success' => true,
            'message' => 'Rol creado correctamente.',
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $this->ensurePermission($request, 'roles.edit');
        $this->ensureCanManageRole($request, $role);

        $data = $this->validateData($request, $role);
        $this->ensureAssignablePermissions($request, $data['permissions'] ?? []);

        if ($role->isSystem() && $data['name'] !== $role->name) {
            throw ValidationException::withMessages([
                'name' => 'El rol Administrador no se puede renombrar.',
            ]);
        }

        DB::transaction(function () use ($data, $role) {
            $role->update(['name' => $data['name']]);
            $role->syncPermissions($data['permissions'] ?? []);
        });

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado correctamente.',
        ]);
    }

    public function destroy(Request $request, Role $role)
    {
        $this->ensurePermission($request, 'roles.delete');
        $this->ensureCanManageRole($request, $role);

        if (! $role->canDelete()) {
            return response()->json([
                'message' => 'No se puede eliminar un rol de sistema o asignado a usuarios.',
            ], 422);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rol eliminado correctamente.',
        ]);
    }

    private function getItems(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $module = $request->input('module');

        return Role::query()
            ->with('permissions')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when(filled($module), function ($query) use ($module) {
                $query->whereHas('permissions', function ($permissionQuery) use ($module) {
                    $permissionQuery->where('name', 'like', "{$module}.%");
                });
            })
            ->latest('id')
            ->paginate(config('crud.pagination', 12))
            ->withQueryString();
    }

    private function validateData(Request $request, ?Role $role = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('roles', 'name')->ignore($role),
            ],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ], [
            'name.required' => 'Debe ingresar el nombre del rol.',
            'name.unique' => 'Ya existe un rol con ese nombre.',
        ]);
    }

    private function permissionsByModule(Request $request)
    {
        $permissions = Permission::orderBy('name')->get();

        if ($this->canManageSystem($request)) {
            return $permissions->groupBy(fn($permission) => explode('.', $permission->name)[0]);
        }

        $allowedPermissions = $request->user()
            ->getAllPermissions()
            ->pluck('name');

        return $permissions
            ->whereIn('name', $allowedPermissions)
            ->groupBy(fn($permission) => explode('.', $permission->name)[0]);
    }

    private function canManageSystem(Request $request): bool
    {
        return $request->user()->can('roles.manage-system');
    }

    private function ensurePermission(Request $request, string $permission): void
    {
        abort_unless($request->user()->can($permission), 403);
    }

    private function ensureCanManageRole(Request $request, Role $role): void
    {
        abort_unless(! $role->isSystem() || $this->canManageSystem($request), 403);
    }

    private function ensureAssignablePermissions(Request $request, array $permissions): void
    {
        if ($this->canManageSystem($request)) {
            return;
        }

        $allowed = $request->user()
            ->getAllPermissions()
            ->pluck('name')
            ->all();

        if (array_diff($permissions, $allowed)) {
            throw ValidationException::withMessages([
                'permissions' => 'Solo puede asignar permisos que ya posee.',
            ]);
        }
    }
}
