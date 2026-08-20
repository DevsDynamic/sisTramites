<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentTypeController extends Controller
{
    public function index()
    {
        return view(
            'document-types.index',
            [
                'types' => $this->getItems()
            ]
        );
    }

    public function cards()
    {
        return view(
            'document-types.partials.results',
            [
                'types' => $this->getItems()
            ]
        );
    }

    private function getItems()
    {
        $active = request('active', '1');

        if (! in_array($active, ['0', '1', 'all'], true)) {
            $active = '1';
        }

        return DocumentType::query()
            ->when(
                request('search'),
                function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where(
                            'name',
                            'like',
                            "%{$search}%"
                        )
                            ->orWhere(
                                'code',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )

            ->when(
                $active !== 'all',
                fn($query) =>
                $query->where(
                    'active',
                    $active === '1'
                )
            )
            ->latest('id')
            ->paginate(
                config('crud.pagination', 12)
            )
            ->withQueryString();
    }

    private function validateData(Request $request, ?DocumentType $type = null): array
    {
        return $request->validate(
            [
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('document_types', 'name')
                        ->ignore($type),
                ],

                'code' => [
                    'nullable',
                    'max:50',
                    Rule::unique('document_types', 'code')
                        ->ignore($type),
                ],
            ],
            [
                'name.required' => 'Debe ingresar el nombre del tipo de documento.',
                'name.unique'   => 'Ya existe un tipo de documento con ese nombre.',
                'code.unique'   => 'El código ingresado ya está siendo utilizado.',
            ]
        );
    }

    private function generateCode(?DocumentType $type = null): string
    {
        if ($type && $type->code) {
            return $type->code;
        }

        $next = $type
            ? $type->id
            : (DocumentType::max('id') + 1);

        return sprintf(
            'TDOC-%04d',
            $next
        );
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['active'] = $request->boolean('active');

        $data['code'] ??=
            $this->generateCode();

        $documentType = DocumentType::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de documento creado correctamente.',
            'item' => $documentType->only(['id', 'name', 'code']),
        ]);
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $data =
            $this->validateData(
                $request,
                $documentType
            );

        $data['active'] =
            $request->boolean('active');

        $data['code'] ??=
            $this->generateCode(
                $documentType
            );

        $documentType->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de documento actualizado correctamente.',
        ]);
    }

    public function active(DocumentType $documentType)
    {
        $documentType->update([
            'active' => ! $documentType->active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
        ]);
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado correctamente.',
        ]);
    }
}
