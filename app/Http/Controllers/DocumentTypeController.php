<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $types = DocumentType::query()
            ->latest()
            ->paginate(12);

        return view(
            'document-types.index',
            compact('types')
        );
    }

    public function cards()
    {
        $types = DocumentType::latest()
            ->paginate(12);

        return view(
            'document-types.partials.cards',
            compact('types')
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
                'name' => 'required|max:255|unique:document_types,name',
                'code' => 'nullable|max:50|unique:document_types,code',
            ],
            [
                'name.required' => 'Debe ingresar el nombre del tipo de documento.',
                'name.unique'   => 'Ya existe un tipo de documento con ese nombre.',
                'code.unique'   => 'El código ingresado ya está siendo utilizado.',
            ]
        );

        $validated['active'] =
            $request->boolean('active');

        /*
        |--------------------------------------------------------------
        | CÓDIGO AUTOMÁTICO
        |--------------------------------------------------------------
        */

        if (empty($validated['code'])) {

            $nextNumber =
                DocumentType::max('id') + 1;

            $validated['code'] =
                sprintf(
                    'TDOC-%04d',
                    $nextNumber
                );
        }

        DocumentType::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de documento creado correctamente.'
        ]);
    }

    public function edit(DocumentType $documentType)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $type =
            DocumentType::findOrFail($id);

        $validated = $request->validate(
            [
                'name' => 'required|max:255|unique:document_types,name,' . $type->id,
                'code' => 'nullable|max:50|unique:document_types,code,' . $type->id,
            ],
            [
                'name.required' => 'Debe ingresar el nombre del tipo de documento.',
                'name.unique'   => 'Ya existe un tipo de documento con ese nombre.',
                'code.unique'   => 'El código ingresado ya está siendo utilizado.',
            ]
        );

        $validated['active'] =
            $request->boolean('active');

        /*
        |--------------------------------------------------------------
        | GENERAR CÓDIGO SI NO EXISTE
        |--------------------------------------------------------------
        */

        if (empty($validated['code'])) {

            $validated['code'] =
                $type->code;

            if (empty($validated['code'])) {

                $validated['code'] =
                    sprintf(
                        'TDOC-%04d',
                        $type->id
                    );
            }
        }

        $type->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de documento actualizado correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $type =
            DocumentType::findOrFail($id);

        $type->update([
            'active' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de documento desactivado correctamente.'
        ]);
    }
}
