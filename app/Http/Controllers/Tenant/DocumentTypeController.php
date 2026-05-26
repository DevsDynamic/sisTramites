<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentType::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('code', 'like', "%{$request->search}%");
        }

        $types = $query->latest()->paginate(10);

        return view('tenant.document-types.index', compact('types'));
    }

    public function create()
    {
        //return view('tenant.document-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            //'code' => 'nullable|max:50|unique:document_types,code',
            'code' => 'nullable|max:50',
        ]);

        $validated['active'] = $request->boolean('active');

        DocumentType::create($validated);

        return back()->with(
            'success',
            'Creado correctamente.'
        );
    }

    public function edit(DocumentType $documentType)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $type = DocumentType::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|max:255',
            'code' => 'nullable|max:50',
        ]);

        $validated['active'] = $request->boolean('active');

        $type->update($validated);

        return back()->with(
            'success',
            'Tipo de documento actualizado.'
        );
    }

    public function destroy($id)
    {
        $type = DocumentType::findOrFail($id);
        $type->delete();

        return back()->with(
            'success',
            'Área eliminada.'
        );
    }
}
