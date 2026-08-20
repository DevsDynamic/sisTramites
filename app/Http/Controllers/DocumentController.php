<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAttachment;
use App\Models\DocumentStatusLog;
use App\Models\DocumentSignatureRequest;
use App\Models\DocumentType;
use App\Models\Area;
use App\Models\Signature;
use App\Models\User;
use App\Services\DocumentSeriesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Enums\DocumentStatus;
use App\Services\PdfSignatureService;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermission($request, 'documents.view');

        return view('documents.index', [
            'documents' => $this->getItems($request),
            'types' => DocumentType::orderBy('name')->get(),
            'areas' => $this->availableAreas($request->user()),
            'canManageAll' => $this->canManageAll($request->user()),
        ]);
    }

    public function cards(Request $request)
    {
        $this->ensurePermission($request, 'documents.view');

        return view('documents.partials.results', [
            'documents' => $this->getItems($request),
            'canManageAll' => $this->canManageAll($request->user()),
        ]);
    }

    public function create()
    {
        $this->ensurePermission(request(), 'documents.create');

        $types = DocumentType::active()->get();

        $areas = $this->availableAreas(auth()->user());
        $signers = User::active()
            ->whereHas('signatures', fn($query) => $query->active())
            ->orderBy('name')
            ->get();

        return view(
            'documents.create',
            compact(
                'types',
                'areas',
                'signers'
            )
        );
    }

    public function store(Request $request, DocumentSeriesService $seriesService)
    {
        $this->ensurePermission($request, 'documents.create');
        $request->validate([
            'subject'          => ['required', 'max:255'],
            'content'          => ['nullable'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'file'             => ['required', 'file', 'mimes:pdf', 'max:51200'],
            'signature_mode' => ['nullable', 'in:none,self,request'],
            'signer_user_id' => ['nullable', 'required_if:signature_mode,request', 'exists:users,id'],
        ]);

        $user   = auth::user();
        $areaId = $request->area_id;

        abort_unless(
            $this->canManageAll($user) || $user->areas()->whereKey($areaId)->exists(),
            403
        );

        if (!$areaId) {
            throw new \Exception('El usuario no tiene un área asignada.');
        }

        DB::beginTransaction();

        try {
            $seriesData = $seriesService->generateWithSeries($request->document_type_id, $areaId);

            $document = Document::create([
                'code'             => $seriesData['code'],
                'subject'          => $request->subject,
                'content'          => $request->content,
                'document_type_id' => $request->document_type_id,
                'document_series_id' => $seriesData['series']->id,
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
                'kind'          => 'primary',
                'uploaded_by'   => $user->id,
            ]);

            // ✅ Guardar log
            DocumentStatusLog::create([
                'document_id' => $document->id,
                'action'      => 'created',
                'description' => 'Documento creado',
                'user_id'     => $user->id,
            ]);

            if ($request->input('signature_mode') === 'request') {
                $canSign = Signature::active()
                    ->where('user_id', $request->input('signer_user_id'))
                    ->exists();

                if (! $canSign) {
                    throw ValidationException::withMessages([
                        'signer_user_id' => 'El usuario seleccionado no tiene una firma activa.',
                    ]);
                }

                DocumentSignatureRequest::create([
                    'document_id' => $document->id,
                    'signer_user_id' => $request->input('signer_user_id'),
                    'requested_by' => $user->id,
                    'sequence' => 1,
                    'status' => 'pending',
                ]);

                $document->update(['status' => DocumentStatus::PENDING]);

                DocumentStatusLog::create([
                    'document_id' => $document->id,
                    'action' => 'signature_requested',
                    'description' => 'Se solicitó la firma de ' . User::find($request->input('signer_user_id'))->name . '.',
                    'user_id' => $user->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message' => 'Documento creado correctamente.',
                'redirect' => route('documents.show', $document->id),
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'No se pudo crear el documento.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function seriesPreview(Request $request, DocumentSeriesService $seriesService)
    {
        $this->ensurePermission($request, 'documents.create');

        $data = $request->validate([
            'document_type_id' => ['required', 'exists:document_types,id'],
            'area_id' => ['required', 'exists:areas,id'],
        ]);

        abort_unless(
            $this->canManageAll($request->user()) || $request->user()->areas()->whereKey($data['area_id'])->exists(),
            403
        );

        $preview = $seriesService->preview($data['document_type_id'], $data['area_id']);

        return response()->json([
            'code' => $preview['code'],
            'series' => $preview['series']->prefix,
            'scope' => $preview['is_global'] ? 'Serie global' : 'Serie específica del área',
        ]);
    }

    public function show($id)
    {
        $document = Document::with([
            'type',
            'creator',
            'attachments',
            'signatureRequests.signer',
            'statusLogs.user', // ← cargar historial
        ])->findOrFail($id);

        $user = auth::user();
        $this->ensureCanViewDocument($user, $document);

        // Solo mostrar firmas si el documento NO está firmado
        $signatures = collect();
        if ($document->status !== DocumentStatus::SIGNED) {
            $signatures = $user->signatures()->active()->get();
        }

        return view('documents.show', compact('document', 'signatures'));
    }

    public function edit(Request $request, Document $document)
    {
        $this->ensureCanManageDraft($request, $document, 'documents.edit');

        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $this->ensureCanManageDraft($request, $document, 'documents.edit');

        $data = $request->validate([
            'subject' => ['required', 'max:255'],
            'content' => ['nullable'],
        ], [
            'subject.required' => 'Debe ingresar el asunto del documento.',
        ]);

        $document->update($data);

        DocumentStatusLog::create([
            'document_id' => $document->id,
            'action' => 'updated',
            'description' => 'Se actualizaron los datos del borrador.',
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('documents.show', $document)
            ->with('success', 'Borrador actualizado correctamente.');
    }

    public function destroy(Request $request, Document $document)
    {
        $this->ensureCanManageDraft($request, $document, 'documents.delete');

        if (! $document->canDelete()) {
            return back()->withErrors([
                'document' => 'Solo se pueden eliminar documentos en estado borrador sin flujo ni firma.',
            ]);
        }

        DB::transaction(function () use ($document) {
            $document->attachments()->each(function (DocumentAttachment $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
                $attachment->delete();
            });

            $document->versions()->each(function ($version) {
                Storage::disk('public')->delete($version->file_path);
                $version->delete();
            });

            $document->statusLogs()->delete();
            $document->delete();
        });

        return redirect()
            ->route('documents.index')
            ->with('success', 'Borrador eliminado correctamente.');
    }

    public function sign(
        Request $request,
        $id,
        PdfSignatureService $pdfSigner
    ) {

        $document = Document::findOrFail($id);

        $signatureRequest = $document->signatureRequests()
            ->where('signer_user_id', auth()->id())
            ->where('status', 'pending')
            ->orderBy('sequence')
            ->first();

        abort_unless(
            $document->created_by === auth()->id() || $signatureRequest,
            403
        );

        if ($signatureRequest) {
            $hasPriorPending = $document->signatureRequests()
                ->where('status', 'pending')
                ->where('sequence', '<', $signatureRequest->sequence)
                ->exists();

            abort_if($hasPriorPending, 422, 'Existe una firma previa pendiente.');
        }

        $request->validate([
            'signature_id' => [
                'required',
                'exists:signatures,id'
            ],
            'appearance_type' => ['nullable', 'in:signature,approval'],
            'placement' => ['nullable', 'in:first,last,all'],
            'orientation' => ['nullable', 'in:horizontal,vertical'],
        ]);

        $signatureOptions = [
            'appearance_type' => $request->input('appearance_type', 'signature'),
            'placement' => $request->input('placement', 'last'),
            'orientation' => $request->input('orientation', 'horizontal'),
        ];

        DB::beginTransaction();

        try {

            // $signature = Signature::findOrFail(
            //     $request->signature_id
            // );

            $signature = Signature::query()
                ->where('id', $request->signature_id)
                ->where('user_id', auth()->id())
                ->where('active', true)
                ->firstOrFail();

            $attachment = $document->attachments()
                ->whereIn('kind', ['primary', 'signed_copy'])
                ->latest('id')
                ->firstOrFail();

            $signedPath = $pdfSigner->sign(
                $attachment,
                $signature,
                $signatureOptions
            );

            $signedAttachment = DocumentAttachment::create([

                'document_id' => $document->id,

                'signature_id' => $signature->id,
                'source_attachment_id' => $attachment->id,

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
                'kind' => 'signed_copy',
                'signature_options' => $signatureOptions,

                'uploaded_by' => auth()->id(),
            ]);

            if ($signatureRequest) {
                $signatureRequest->update([
                    'signature_id' => $signature->id,
                    'signed_attachment_id' => $signedAttachment->id,
                    'status' => 'signed',
                    'signed_at' => now(),
                ]);
            }

            $hasPendingRequests = $document->signatureRequests()
                ->where('status', 'pending')
                ->exists();

            $document->update([
                'status' => $hasPendingRequests
                    ? DocumentStatus::PENDING
                    : DocumentStatus::SIGNED,
            ]);

            DocumentStatusLog::create([

                'document_id' => $document->id,

                'action' => 'signed',

                'description' =>
                ($signatureOptions['appearance_type'] === 'approval'
                    ? 'Visto bueno digital aplicado con '
                    : 'Documento firmado con ') . $this->signatureTypeLabel($signature),

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

    private function ensureCanManageDraft(
        Request $request,
        Document $document,
        string $permission
    ): void {
        $user = $request->user();

        abort_unless(
            ($this->canManageAll($user) && $user->can($permission))
                || ($document->created_by === $user->id && $user->can($permission)),
            403
        );

        abort_unless($document->canEdit(), 422);
    }

    private function getItems(Request $request)
    {
        $user = $request->user();
        $search = trim((string) $request->input('search'));
        $status = $request->input('status');
        $typeId = $request->input('document_type_id');
        $areaId = $request->input('area_id');
        $sort = $request->input('sort', 'activity');
        $allowedStatuses = array_map(fn($case) => $case->value, DocumentStatus::cases());

        if (! in_array($status, $allowedStatuses, true)) {
            $status = null;
        }

        $query = $this->documentScope($user)
            ->with(['type', 'creator', 'area', 'attachments'])
            ->withCount([
                'signatureRequests',
                'signatureRequests as pending_signature_requests_count' => fn($query) => $query->where('status', 'pending'),
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($documentQuery) use ($search) {
                    $documentQuery->where('code', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->when($status, fn($query) => $query->where('status', $status))
            ->when(filled($typeId), fn($query) => $query->where('document_type_id', $typeId))
            ->when(filled($areaId), fn($query) => $query->where('area_id', $areaId));

        match ($sort) {
            'newest' => $query->latest('created_at'),
            'code' => $query->orderBy('code'),
            default => $query->latest('updated_at'),
        };

        return $query
            ->paginate(config('crud.pagination', 12))
            ->withQueryString();
    }

    private function documentScope($user)
    {
        $query = Document::query();

        if ($this->canManageAll($user)) {
            return $query;
        }

        $areaIds = $user->areas()->pluck('areas.id');

        return $query->where(function ($documentQuery) use ($user, $areaIds) {
            $documentQuery->where('created_by', $user->id)
                ->when($areaIds->isNotEmpty(), function ($query) use ($areaIds) {
                    $query->orWhereHas('flows', fn($flowQuery) => $flowQuery->whereIn('to_area_id', $areaIds));
                });
        });
    }

    private function availableAreas($user)
    {
        return $this->canManageAll($user)
            ? Area::active()->orderBy('name')->get()
            : $user->areas()->active()->orderBy('name')->get();
    }

    private function canManageAll($user): bool
    {
        return $user->isSystemOwner() || $user->can('documents.manage-all');
    }

    private function ensureCanViewDocument($user, Document $document): void
    {
        abort_unless($user->isSystemOwner() || $user->can('documents.view'), 403);

        if ($this->canManageAll($user)) {
            return;
        }

        $belongsToUser = $document->created_by === $user->id;
        $belongsToArea = $user->areas()
            ->whereHas('incomingFlows', fn($query) => $query->where('document_id', $document->id))
            ->exists();

        $hasSignatureRequest = $document->signatureRequests()
            ->where('signer_user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        abort_unless($belongsToUser || $belongsToArea || $hasSignatureRequest, 403);
    }

    private function ensurePermission(Request $request, string $permission): void
    {
        abort_unless($request->user()->isSystemOwner() || $request->user()->can($permission), 403);
    }

    private function signatureTypeLabel(Signature $signature): string
    {
        return $signature->type === 'official'
            ? 'firma digital certificada'
            : 'firma visual';
    }
}
