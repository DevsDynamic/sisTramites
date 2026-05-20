<?php

namespace App\Http\Controllers\Tenant\Documents;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentFlow;
use App\Models\Tenant\DocumentStatusLog;
use App\Services\DocumentSeriesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    protected DocumentSeriesService $seriesService;

    public function __construct(DocumentSeriesService $seriesService)
    {
        $this->seriesService = $seriesService;
    }

    /**
     * LISTADO
     */
    public function index(Request $request)
    {
        // dd(tenant(), config('database.connections.tenant.database'));
        $query = Document::query();

        if ($request->search) {
            $query->where('subject', 'like', "%{$request->search}%")
                ->orWhere('code', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->paginate(10);

        return view('tenant.documents.index', compact('documents'));
    }

    

    /**
     * FORM CREATE
     */
    public function create()
    {
        return view('tenant.documents.create');
    }

    /**
     * CREAR DOCUMENTO (CORE ENTERPRISE)
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'document_type_id' => 'required',
            'to_area_id' => 'required',
        ]);

        $document = app(\App\Services\DocumentService::class)
            ->create($request->all(), $request->user());

        return redirect()
            ->route('tenant.documents.index')
            ->with('success', 'Documento creado correctamente');
    }

    /* DETALLE */
    public function show(Document $document)
    {
        $document->load([
            'flows',
            'logs'
        ]);

        return view('tenant.documents.show', compact('document'));
    }
}
