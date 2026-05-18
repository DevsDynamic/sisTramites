<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Area;
use App\Models\Tenant\TenantUser;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /* INDEX */
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

    /* CREATE */
    public function create()
    {
        //
    }

    /* STORE */
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

    /* EDIT */
    public function edit(Area $area)
    {
        //
    }

    /* UPDATE */
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

    /* DESTROY */
    public function destroy($id)
    {
        $area = Area::findOrFail($id);

        $area->delete();

        return back()->with(
            'success',
            'Área eliminada.'
        );
    }
}
