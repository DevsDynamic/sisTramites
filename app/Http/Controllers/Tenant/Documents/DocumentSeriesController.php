<?php

namespace App\Http\Controllers\Tenant\Documents;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentFlow;
use App\Models\Tenant\DocumentSeries;
use App\Models\Tenant\DocumentType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DocumentSeriesController extends Controller
{
    public function index()
    {
        $series = DocumentSeries::with('documentType')
            ->latest()
            ->paginate(20);

        return view(
            'tenant.documents.series.index',
            compact('series')
        );
    }

    public function create()
    {
        $types = DocumentType::all();

        return view(
            'tenant.documents.series.create',
            compact('types')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type_id' => 'required',
            'prefix' => 'required',
            'current_number' => 'required|integer',
        ]);

        DocumentSeries::create([
            ...$request->all(),
            'tenant_id' => tenant_id(),
        ]);

        return redirect()
            ->route('tenant.document-series.index')
            ->with('success', 'Serie creada');
    }

    public function edit(DocumentSeries $documentSeries)
    {
        $types = DocumentType::all();

        return view(
            'tenant.documents.series.edit',
            compact(
                'documentSeries',
                'types'
            )
        );
    }

    public function update(
        Request $request,
        DocumentSeries $documentSeries
    ) {

        $documentSeries->update($request->all());

        return back()->with(
            'success',
            'Serie actualizada'
        );
    }

    public function destroy(DocumentSeries $documentSeries)
    {
        $documentSeries->delete();

        return back()->with(
            'success',
            'Serie eliminada'
        );
    }
}
