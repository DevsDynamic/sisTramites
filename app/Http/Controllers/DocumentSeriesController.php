<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Document;
use App\Models\DocumentFlow;
use App\Models\DocumentSeries;
use App\Models\DocumentType;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DocumentSeriesController extends Controller
{
    public function index()
    {
        $series = DocumentSeries::with([
            'documentType',
            'area'
        ])
            ->search(request('search'))
            ->latest()
            ->paginate(20);

        $types = DocumentType::active()->get();

        $areas = Area::active()->get();

        return view(
            'document-series.index',
            compact('series', 'types', 'areas')
        );
    }

    public function cards()
    {
        $series = DocumentSeries::with([
            'documentType',
            'area'
        ])
            ->latest()
            ->paginate(20);

        return view(
            'document-series.partials.cards',
            compact('series')
        );
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->merge([
            'reset_yearly' => $request->boolean('reset_yearly'),
            'active' => $request->boolean('active'),
        ]);

        $validated = $request->validate(
            [
                'document_type_id' => [
                    'required',
                    Rule::unique('document_series')
                        ->where(
                            fn($q) =>
                            $q->where('area_id', $request->area_id)
                        ),
                ],

                'area_id' => [
                    'nullable',
                    'exists:areas,id'
                ],

                'prefix' => [
                    'required',
                    'max:20'
                ],

                'current_number' => [
                    'required',
                    'integer',
                    'min:0'
                ],

                'padding' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:15'
                ],

                'reset_yearly' => [
                    'nullable',
                    'boolean'
                ],

                'active' => [
                    'nullable',
                    'boolean'
                ],
            ],
            [
                'document_type_id.unique' =>
                'Ya existe una serie para este tipo de documento en el área seleccionada.',
            ]
        );

        DocumentSeries::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Serie creada correctamente.'
        ]);
    }

    public function edit(DocumentSeries $documentSeries)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $documentSeries =
            DocumentSeries::findOrFail($id);

        $request->merge([
            'reset_yearly' => $request->boolean('reset_yearly'),
            'active' => $request->boolean('active'),
        ]);

        $validated = $request->validate(
            [
                'document_type_id' => [
                    'required',
                    Rule::unique('document_series')
                        ->ignore($documentSeries->id)
                        ->where(
                            fn($q) =>
                            $q->where('area_id', $request->area_id)
                        ),
                ],

                'area_id' => [
                    'nullable',
                    'exists:areas,id'
                ],

                'prefix' => [
                    'required',
                    'max:20'
                ],

                'current_number' => [
                    'required',
                    'integer',
                    'min:0'
                ],

                'padding' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:15'
                ],

                'reset_yearly' => [
                    'nullable',
                    'boolean'
                ],

                'active' => [
                    'nullable',
                    'boolean'
                ],
            ],
            [
                'document_type_id.unique' =>
                'Ya existe una serie para este tipo de documento en el área seleccionada.',
            ]
        );

        $documentSeries->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Serie actualizada correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $documentSeries =
            DocumentSeries::findOrFail($id);

        $documentSeries->update([
            'active' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Serie desactivada correctamente.'
        ]);
    }
}
