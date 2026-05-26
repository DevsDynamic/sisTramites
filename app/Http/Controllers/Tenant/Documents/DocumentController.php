<?php

namespace App\Http\Controllers\Tenant\Documents;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentAttachment;
use App\Models\Tenant\DocumentFlow;
use App\Models\Tenant\DocumentStatusLog;
use App\Models\Tenant\DocumentType;
use App\Models\Tenant\Signature;
use App\Services\DocumentSeriesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    // protected DocumentSeriesService $seriesService;

    // public function __construct(DocumentSeriesService $seriesService)
    // {
    //     $this->seriesService = $seriesService;
    // }

    // /**
    //  * LISTADO
    //  */
    // public function index(Request $request)
    // {
    //     // dd(tenant(), config('database.connections.tenant.database'));
    //     $query = Document::query();

    //     if ($request->search) {
    //         $query->where('subject', 'like', "%{$request->search}%")
    //             ->orWhere('code', 'like', "%{$request->search}%");
    //     }

    //     if ($request->status) {
    //         $query->where('status', $request->status);
    //     }

    //     $documents = $query->latest()->paginate(10);

    //     return view('tenant.documents.index', compact('documents'));
    // }



    // /**
    //  * FORM CREATE
    //  */
    // public function create()
    // {
    //     return view('tenant.documents.create');
    // }

    // /**
    //  * CREAR DOCUMENTO (CORE ENTERPRISE)
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'subject' => 'required',
    //         'document_type_id' => 'required',
    //         'to_area_id' => 'required',
    //     ]);

    //     $document = app(\App\Services\DocumentService::class)
    //         ->create($request->all(), $request->user());

    //     return redirect()
    //         ->route('tenant.documents.index')
    //         ->with('success', 'Documento creado correctamente');
    // }

    // /* DETALLE */
    // public function show(Document $document)
    // {
    //     $document->load([
    //         'flows',
    //         'logs'
    //     ]);

    //     return view('tenant.documents.show', compact('document'));
    // }

    public function index()
    {
        $documents = Document::with([
            'type',
            'creator',
            'attachments',
        ])
            ->latest()
            ->paginate(12);

        return view(
            'tenant.documents.index',
            compact('documents')
        );
    }

    public function create()
    {
        $types = DocumentType::active()->get();

        return view(
            'tenant.documents.create',
            compact('types')
        );
    }

    public function store(
        Request $request,
        DocumentSeriesService $seriesService
    ) {

        $validated = $request->validate([

            'subject' => [
                'required',
                'max:255'
            ],

            'content' => [
                'nullable'
            ],

            'document_type_id' => [
                'required',
                'exists:tenant.document_types,id'
            ],

            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240'
            ],
        ]);

        $user = \App\Models\Tenant\TenantUser::first();

$user = new \App\Models\Tenant\TenantUser();

dd([
    'default' => DB::connection()->getDatabaseName(),
    'tenant' => DB::connection('tenant')->getDatabaseName(),
]);

        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | GENERAR CORRELATIVO
        |--------------------------------------------------------------------------
        */

            $code = $seriesService->generate(
                $request->document_type_id,
                auth('tenant')->user()->area_id ?? null
            );

            /*
        |--------------------------------------------------------------------------
        | DOCUMENT
        |--------------------------------------------------------------------------
        */

            $document = Document::create([

                'code' => $code,

                'subject' => $request->subject,

                'content' => $request->content,

                'document_type_id' =>
                $request->document_type_id,

                'area_id' =>
                auth()->user()->area_id,

                'status' => 'draft',

                'created_by' => auth()->id(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | FILE
        |--------------------------------------------------------------------------
        */

            $path = $request
                ->file('file')
                ->store(
                    'documents',
                    'public'
                );

            DocumentAttachment::create([

                'document_id' => $document->id,

                'file_path' => $path,

                'original_name' =>
                $request->file('file')
                    ->getClientOriginalName(),

                'mime_type' =>
                $request->file('file')
                    ->getMimeType(),

                'file_size' =>
                $request->file('file')
                    ->getSize(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Documento creado correctamente.'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Document $document)
    {
        $document->load([
            'type',
            'creator',
            'attachments',
        ]);

        $signatures = Signature::where(
            'user_id',
            auth()->id()
        )
            ->active()
            ->get();

        return view(
            'tenant.documents.show',
            compact(
                'document',
                'signatures'
            )
        );
    }
}
