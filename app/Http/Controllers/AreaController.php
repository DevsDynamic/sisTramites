<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::query()
            ->latest()
            ->paginate(12);

        return view(
            'areas.index',
            compact('areas')
        );
    }

    public function cards()
    {
        $areas = Area::latest()->paginate(12);

        return view(
            'areas.partials.cards',
            compact('areas')
        );
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|max:255|unique:areas,name',
                'code' => 'nullable|max:50|unique:areas,code',
                'description' => 'nullable|max:1000',
            ],
            [
                'name.required' => 'Debe ingresar el nombre del área.',
                'name.unique'   => 'Ya existe un área con ese nombre.',
                'code.unique'   => 'El código ingresado ya está siendo utilizado por otra área.',
            ]
        );

        $validated['active'] = $request->boolean('active');

        /* CÓDIGO AUTOMÁTICO */
        if (empty($validated['code'])) {

            $nextNumber = Area::max('id') + 1;

            $validated['code'] = sprintf(
                'AREA-%04d',
                $nextNumber
            );
        }

        Area::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Área creada correctamente.'
        ]);
    }

    public function edit(Area $area)
    {
        //
    }

    public function update(Request $request, $id) {

        $area = Area::findOrFail($id);

        $validated = $request->validate(
            [
                'name' => 'required|max:255|unique:areas,name,' . $area->id,
                'code' => 'nullable|max:50|unique:areas,code,' . $area->id,
                'description' => 'nullable|max:1000',
            ],
            [
                'name.required' => 'Debe ingresar el nombre del área.',
                'name.unique'   => 'Ya existe un área con ese nombre.',
                'code.unique'   => 'El código ingresado ya está siendo utilizado por otra área.',
            ]
        );

        $validated['active'] =
            $request->boolean('active');

        /* GENERAR CÓDIGO SI NO EXISTE */
        if (empty($validated['code'])) {

            $validated['code'] = $area->code;

            if (empty($validated['code'])) {

                $validated['code'] =
                    sprintf(
                        'AREA-%04d',
                        $area->id
                    );
            }
        }

        $area->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Área actualizada correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->update([
            'active' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Área desactivada correctamente.'
        ]);
    }
}
