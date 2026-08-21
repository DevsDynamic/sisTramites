<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Services\PlanLimitService;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermission($request, 'areas.view');

        return view('areas.index', [
            'areas' => $this->getItems($request),
        ]);
    }

    public function cards(Request $request)
    {
        $this->ensurePermission($request, 'areas.view');

        return view('areas.partials.results', [
            'areas' => $this->getItems($request),
        ]);
    }

    public function store(Request $request, PlanLimitService $planLimits)
    {
        $this->ensurePermission($request, 'areas.create');
        $planLimits->ensureAvailable('areas');

        $data = $this->validateData($request);
        $data['active'] = $request->boolean('active');
        $data['code'] ??= $this->generateCode();

        $area = Area::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Área creada correctamente.',
            'item' => $area->only(['id', 'name', 'code']),
        ]);
    }

    public function update(Request $request, Area $area)
    {
        $this->ensurePermission($request, 'areas.edit');

        $data = $this->validateData($request, $area);
        $data['active'] = $request->boolean('active');
        $data['code'] ??= $this->generateCode($area);

        $area->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Área actualizada correctamente.',
        ]);
    }

    public function active(Request $request, Area $area)
    {
        $this->ensurePermission($request, 'areas.edit');

        $area->update(['active' => ! $area->active]);

        return response()->json([
            'success' => true,
            'message' => 'Estado del área actualizado correctamente.',
        ]);
    }

    public function destroy(Request $request, Area $area)
    {
        $this->ensurePermission($request, 'areas.delete');

        if (! $area->canDelete()) {
            return response()->json([
                'message' => 'No se puede eliminar un área con registros relacionados.',
            ], 422);
        }

        $area->delete();

        return response()->json([
            'success' => true,
            'message' => 'Área eliminada correctamente.',
        ]);
    }

    private function getItems(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $active = $request->input('active', '1');

        if (! in_array($active, ['0', '1', 'all'], true)) {
            $active = '1';
        }

        return Area::query()
            ->withCount(['users', 'documentSeries', 'documents'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($areaQuery) use ($search) {
                    $areaQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($active !== 'all', fn($query) => $query->where('active', $active === '1'))
            ->latest('id')
            ->paginate(config('crud.pagination', 12))
            ->withQueryString();
    }

    private function validateData(Request $request, ?Area $area = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('areas', 'name')->ignore($area),
            ],
            'code' => [
                'nullable',
                'max:50',
                Rule::unique('areas', 'code')->ignore($area),
            ],
            'description' => ['nullable', 'max:1000'],
            'active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Debe ingresar el nombre del área.',
            'name.unique' => 'Ya existe un área con ese nombre.',
            'code.unique' => 'El código ingresado ya está siendo utilizado.',
        ]);
    }

    private function generateCode(?Area $area = null): string
    {
        if ($area?->code) {
            return $area->code;
        }

        return sprintf('AREA-%04d', $area?->id ?? (Area::max('id') + 1));
    }

    private function ensurePermission(Request $request, string $permission): void
    {
        abort_unless(
            $request->user()->isSystemOwner()
                || $request->user()->can($permission),
            403
        );
    }
}
