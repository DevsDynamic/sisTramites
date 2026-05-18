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
        return view('tenant.document-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:document_types,code',
        ]);

        DocumentType::create([
            'name' => $request->name,
            'code' => $request->code,
            'is_active' => true,
            'tenant_id' => tenant_id(),
        ]);

        return redirect()->route('tenant.document-types.index')
            ->with('success', 'Creado correctamente');
    }

    public function edit(DocumentType $documentType)
    {
        return view('tenant.document-types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $documentType->update($request->only('name', 'code', 'is_active'));

        return redirect()->route('tenant.document-types.index')
            ->with('success', 'Actualizado correctamente');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return back()->with('success', 'Eliminado');
    }
}