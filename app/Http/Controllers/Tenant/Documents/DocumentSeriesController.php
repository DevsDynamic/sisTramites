<?php

namespace App\Http\Controllers\Tenant\Documents;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Area;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentFlow;
use App\Models\Tenant\DocumentSeries;
use App\Models\Tenant\DocumentType;
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
            'tenant.documents.series.index',
            compact('series', 'types', 'areas')
        );
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        /* NORMALIZAR CHECKBOX */
        $request->merge([
            'reset_yearly' => $request->boolean('reset_yearly'),
            'active' => $request->boolean('active'),
        ]);

        $validated = $request->validate([
            'document_type_id' => [
                'required',
                'exists:tenant.document_types,id'
            ],
            'area_id' => [
                'nullable',
                'exists:tenant.areas,id'
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

            /* UNIQUE COMBINADO */
            'document_type_id' => [
                'required',
                Rule::unique('tenant.document_series')
                    ->where(function ($query) use ($request) {

                        return $query
                            ->where('area_id', $request->area_id);
                    }),
            ],
        ]);

        DocumentSeries::create($validated);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Serie creada correctamente.'
        // ]);
        return back()->with(
            'success',
            'Serie creada correctamente.'
        );
    }

    public function edit(DocumentSeries $documentSeries)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $documentSeries = DocumentSeries::findOrFail($id);

        $request->merge([
            'reset_yearly' => $request->boolean('reset_yearly'),
            'active' => $request->boolean('active'),
        ]);

        $validated = $request->validate([
            'document_type_id' => [
                'required',
                'exists:tenant.document_types,id'
            ],
            'area_id' => [
                'nullable',
                'exists:tenant.areas,id'
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
            'document_type_id' => [
                Rule::unique('tenant.document_series')
                    ->ignore($documentSeries->id)
                    ->where(function ($query) use ($request) {

                        return $query
                            ->where('area_id', $request->area_id);
                    }),
            ],
        ]);

        /* UPDATE */
        $documentSeries->update($validated);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Serie actualizada correctamente.',
        // ]);
        return back()->with(
            'success',
            'Serie actualizada correctamente.'
        );
    }

    public function destroy($id)
    {
        $documentSeries = DocumentSeries::findOrFail($id);
        $documentSeries->update([
            'active' => false
        ]);

        return back()->with(
            'success',
            'Serie desactivada correctamente.'
        );
    }
}
