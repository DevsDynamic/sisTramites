<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Area;
use App\Models\Tenant\TenantUser;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::query()
            ->latest()
            ->paginate(12);

        return view(
            'tenant.areas.index',
            compact('areas')
        );
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'code' => 'nullable|max:50',
            'description' => 'nullable|max:1000',
        ]);

        $validated['active'] = $request->boolean('active');

        Area::create($validated);

        return back()->with(
            'success',
            'Área creada correctamente.'
        );
    }

    public function edit(Area $area)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|max:255',
            'code' => 'nullable|max:50',
            'description' => 'nullable|max:1000',
        ]);

        $validated['active'] = $request->boolean('active');

        $area->update($validated);

        return back()->with(
            'success',
            'Área actualizada.'
        );
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->update([
            'active' => false
        ]);

        return back()->with(
            'success',
            'Area desactivada correctamente.'
        );
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Area desactivada correctamente.'
        // ]);
    }
}
