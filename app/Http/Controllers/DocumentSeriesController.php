<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\DocumentSeries;
use App\Models\DocumentType;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class DocumentSeriesController extends Controller
{
    public function index()
    {
        return view('document-series.index', [
            'series' => $this->getItems(),
            'types' => DocumentType::active()->get(),
            'areas' => Area::active()->get(),
        ]);
    }

    public function cards()
    {
        return view(
            'document-series.partials.results',
            [
                'series' => $this->getItems()
            ]
        );
    }

    private function getItems()
    {
        $search = trim((string) request('search'));
        $active = request('active', '1');
        $typeId = request('document_type_id');
        $areaId = request('area_id');

        if (! in_array($active, ['0', '1', 'all'], true)) {
            $active = '1';
        }

        return DocumentSeries::query()
            ->with(['documentType', 'area'])

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('prefix', 'like', "%{$search}%")
                        ->orWhereHas('documentType', function ($typeQuery) use ($search) {
                            $typeQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        })
                        ->orWhereHas('area', function ($areaQuery) use ($search) {
                            $areaQuery->where('name', 'like', "%{$search}%");
                        });

                    if (is_numeric($search)) {
                        $q->orWhere('current_number', (int) $search);
                    }
                });
            })

            ->when(
                filled($typeId),
                fn($query) => $query->where(
                    'document_type_id',
                    $typeId
                )
            )

            ->when($areaId === 'global', fn($query) => $query->whereNull('area_id'))

            ->when(
                filled($areaId) && $areaId !== 'global',
                fn($query) => $query->where('area_id', $areaId)
            )

            ->when(
                $active !== 'all',
                fn($query) => $query->where('active', $active === '1')
            )

            ->latest('id')
            ->paginate(config('crud.pagination', 12))
            ->withQueryString();
    }

    private function validateData(Request $request, ?DocumentSeries $series = null): array
    {
        return $request->validate(
            [
                'document_type_id' => [
                    'required',
                    Rule::unique('document_series')
                        ->ignore($series?->id)
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
            ],
            [
                'document_type_id.unique' =>
                'Ya existe una serie para este tipo de documento en el área seleccionada.',
            ]
        );
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['reset_yearly'] = $request->boolean('reset_yearly');
        $data['active'] = $request->boolean('active');

        $series = DocumentSeries::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Serie creada correctamente.',
            'item' => $series->only(['id', 'document_type_id', 'area_id', 'prefix']),
        ]);
    }

    public function update(Request $request, DocumentSeries $documentSeries)
    {
        $data = $this->validateData($request, $documentSeries);
        $data['reset_yearly'] = $request->boolean('reset_yearly');
        $data['active'] = $request->boolean('active');

        $documentSeries->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Serie actualizada correctamente.',
        ]);
    }

    public function active(DocumentSeries $documentSeries)
    {
        $documentSeries->update([
            'active' => ! $documentSeries->active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
        ]);
    }

    public function destroy(DocumentSeries $documentSeries)
    {
        $documentSeries->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado correctamente.',
        ]);
    }
}
