<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAttachment;
use App\Models\DocumentStatusLog;
use App\Models\DocumentType;
use App\Models\Signature;
use App\Services\DocumentSeriesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\DocumentStatus;
use App\Services\PdfSignatureService;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['type', 'creator', 'attachments'])
            ->latest()
            ->paginate(12);

        return view('documents.index', compact('documents'));
    }

    public function create()
    {
        $types = DocumentType::active()->get();

        $areas = auth()
            ->user()
            ->areas()
            ->active()
            ->get();

        return view(
            'documents.create',
            compact(
                'types',
                'areas'
            )
        );
    }

    public function store(Request $request, DocumentSeriesService $seriesService)
    {
        $request->validate([
            'subject'          => ['required', 'max:255'],
            'content'          => ['nullable'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'file'             => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $user   = auth::user();
        $areaId = $request->area_id;

        if (!$areaId) {
            throw new \Exception('El usuario no tiene un área asignada.');
        }

        DB::beginTransaction();

        try {
            $code = $seriesService->generate($request->document_type_id, $areaId);

            $document = Document::create([
                'code'             => $code,
                'subject'          => $request->subject,
                'content'          => $request->content,
                'document_type_id' => $request->document_type_id,
                'area_id'          => $areaId,
                'status'           => DocumentStatus::DRAFT,
                'created_by'       => $user->id,
            ]);

            $file = $request->file('file');
            $path = $file->store('documents', 'public');

            DocumentAttachment::create([
                'document_id'   => $document->id,
                'file_name'     => $file->getClientOriginalName(),
                'original_name' => $file->getClientOriginalName(),
                'file_path'     => $path,
                'file_type'     => $file->getMimeType(),
                'mime_type'     => $file->getMimeType(),
                'file_size'     => $file->getSize(), // ← columna corregida
                'is_signed'     => false,
                'uploaded_by'   => $user->id,
            ]);

            // ✅ Guardar log
            DocumentStatusLog::create([
                'document_id' => $document->id,
                'action'      => 'created',
                'description' => 'Documento creado',
                'user_id'     => $user->id,
            ]);

            DB::commit();

            return response()->json([
                'success'  => true,
                'message' => 'Documento creado correctamente.',
                'redirect' => route('documents.show', $document->id),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $document = Document::with([
            'type',
            'creator',
            'attachments',
            'statusLogs.user', // ← cargar historial
        ])->findOrFail($id);

        $user = auth::user();

        // Solo mostrar firmas si el documento NO está firmado
        $signatures = collect();
        if ($document->status !== DocumentStatus::SIGNED) {
            $signatures = $user->signatures()->active()->get();
        }

        return view('documents.show', compact('document', 'signatures'));
    }

    public function sign(
        Request $request,
        $id,
        PdfSignatureService $pdfSigner
    ) {

        $document = Document::findOrFail($id);

        if (
            $document->status ===
            DocumentStatus::SIGNED
        ) {
            return back()->withErrors([
                'message' => 'Documento ya firmado.'
            ]);
        }

        $request->validate([
            'signature_id' => [
                'required',
                'exists:signatures,id'
            ]
        ]);

        DB::beginTransaction();

        try {

            $signature = Signature::findOrFail(
                $request->signature_id
            );

            $attachment = $document
                ->attachments()
                ->where('is_signed', false)
                ->latest()
                ->firstOrFail();

            $signedPath = $pdfSigner->sign(
                $attachment,
                $signature
            );

            DocumentAttachment::create([

                'document_id' => $document->id,

                'file_name' => basename(
                    $signedPath
                ),

                'original_name' =>
                'FIRMADO - ' .
                    $attachment->original_name,

                'file_path' => $signedPath,

                'file_type' => 'application/pdf',

                'mime_type' => 'application/pdf',

                'file_size' => Storage::disk('public')
                    ->size($signedPath),

                'is_signed' => true,

                'uploaded_by' => auth()->id(),
            ]);

            $document->update([
                'status' => DocumentStatus::SIGNED
            ]);

            DocumentStatusLog::create([

                'document_id' => $document->id,

                'action' => 'signed',

                'description' =>
                'Documento firmado con ' .
                    strtoupper($signature->type),

                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return back()->with(
                'success',
                'Documento firmado correctamente.'
            );
        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return back()->withErrors([
                'message' => $e->getMessage()
            ]);
        }
    }
}
