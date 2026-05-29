<?php

namespace App\Http\Controllers\Tenant\Documents;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentAttachment;
use App\Models\Tenant\DocumentStatusLog;
use App\Models\Tenant\DocumentType;
use App\Models\Tenant\Signature;
use App\Services\DocumentSeriesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Enums\DocumentStatus;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with(['type', 'creator', 'attachments'])
            ->latest()
            ->paginate(12);

        return view('tenant.documents.index', compact('documents'));
    }

    public function create()
    {
        $types = DocumentType::active()->get();
        return view('tenant.documents.create', compact('types'));
    }

    public function store(Request $request, DocumentSeriesService $seriesService)
    {
        $request->validate([
            'subject'          => ['required', 'max:255'],
            'content'          => ['nullable'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'file'             => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $user   = auth('tenant')->user();
        $areaId = $user->areas()->value('areas.id');

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
                'size'          => $file->getSize(),
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
                'redirect' => route('tenant.documents.show', $document->id),
                // 'redirect' => route('tenant.documents.index'),
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

        $user = auth('tenant')->user();

        // Solo mostrar firmas si el documento NO está firmado
        $signatures = collect();
        if ($document->status !== DocumentStatus::SIGNED) {
            $signatures = $user->signatures()->active()->get();
        }

        return view('tenant.documents.show', compact('document', 'signatures'));
    }

    public function sign(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        // ✅ Validar que no esté ya firmado
        if ($document->status->value === DocumentStatus::SIGNED->value) {
            return back()->withErrors(['message' => 'El documento ya fue firmado.']);
        }

        $request->validate([
            'signature_id' => ['required', 'exists:tenant.signatures,id'],
        ]);
        
        try {
            $signature = Signature::findOrFail($request->signature_id);

            if ($signature->user_id !== auth('tenant')->id()) {
                abort(403);
            }

            $attachment = $document->attachments()
                ->where('is_signed', false)
                ->latest()
                ->first();

            if (!$attachment) {
                return back()->withErrors(['message' => 'Documento sin archivo original.']);
            }

            $signedName = 'SIGNED-' . basename($attachment->file_path);
            $signedPath = 'documents/signed/' . $signedName;

            Storage::disk('public')->copy($attachment->file_path, $signedPath);

            // ✅ Crear attachment firmado con is_signed = true
            DocumentAttachment::create([
                'document_id'   => $document->id,
                'file_name'     => $signedName,
                'original_name' => 'FIRMADO - ' . $attachment->original_name,
                'file_path'     => $signedPath,
                'file_type'     => $attachment->file_type,
                'mime_type'     => $attachment->mime_type,
                'file_size'     => $attachment->file_size,
                'size'          => $attachment->size,
                'is_signed'     => true, // ← marcado como firmado
                'uploaded_by'   => auth('tenant')->id(),
            ]);

            $document->update(['status' => DocumentStatus::SIGNED]);

            // ✅ Guardar log
            DocumentStatusLog::create([
                'document_id' => $document->id,
                'action'      => 'signed',
                'description' => 'Documento firmado con firma: ' . strtoupper($signature->type),
                'user_id'     => auth('tenant')->id(),
            ]);

            \Log::info('document signed', ['document_id' => $document->id]);

            return back()->with('success', 'Documento firmado correctamente.');
        } catch (\Throwable $e) {

            \Log::error('document sign error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);

            return back()->withErrors([
                'message' => 'Ocurrió un error al firmar el documento. ' . $e->getMessage()
            ]);
        }
    }
}
