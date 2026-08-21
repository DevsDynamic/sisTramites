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
use App\Services\WorkflowExecutionService;
use App\Services\PlanLimitService;
use App\Models\WorkflowTemplate;

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
            ->where('id', '!=', auth()->id())
            ->whereHas('signatures', fn($query) => $query->active())
            ->orderBy('name')
            ->get();
        $workflowTemplates = WorkflowTemplate::active()->with(['documentType', 'originArea', 'steps'])->orderBy('name')->get();

        return view(
            'documents.create',
            compact(
                'types',
                'areas',
                'signers'
                ,'workflowTemplates'
            )
        );
    }

    public function store(Request $request, DocumentSeriesService $seriesService, WorkflowExecutionService $workflowExecution, PlanLimitService $planLimits)
    {
        $this->ensurePermission($request, 'documents.create');
        $planLimits->ensureAvailable('documents');
        $request->validate([
            'subject'          => ['required', 'max:255'],
            'content'          => ['nullable'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'file'             => ['required', 'file', 'mimes:pdf', 'max:51200'],
            'signature_mode' => ['nullable', 'in:none,self,request'],
            'signer_user_id' => ['nullable', 'required_if:signature_mode,request', 'exists:users,id'],
            'workflow_template_id' => ['nullable', 'exists:workflow_templates,id'],
        ]);
        $planLimits->ensureStorageAvailable((int) $request->file('file')->getSize());

        $user   = auth::user();
        $areaId = $request->area_id;

        if ($request->filled('workflow_template_id') && $request->input('signature_mode', 'none') !== 'none') {
            throw ValidationException::withMessages([
                'signature_mode' => 'Use el flujo para asignar responsables; no combine un flujo con una solicitud puntual de firma.',
            ]);
        }

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
            $path = $file->store('documents', 'local');

            DocumentAttachment::create([
                'document_id'   => $document->id,
                'file_name'     => $file->getClientOriginalName(),
                'original_name' => $file->getClientOriginalName(),
                'file_path'     => $path,
                'storage_disk'  => 'local',
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

            if ($request->filled('workflow_template_id')) {
                $workflowExecution->start(
                    $document,
                    WorkflowTemplate::findOrFail($request->integer('workflow_template_id')),
                    $user
                );
            }

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

            if ($request->input('signature_mode') === 'self') {
                DocumentStatusLog::create([
                    'document_id' => $document->id,
                    'action' => 'self_signature_selected',
                    'description' => 'El creador preparó el documento para firmarlo personalmente.',
                    'user_id' => $user->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success'  => true,
                'message' => $request->input('signature_mode') === 'self'
                    ? 'Documento creado. Ahora selecciona la firma que deseas aplicar.'
                    : 'Documento creado correctamente.',
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

    public function show($id, WorkflowExecutionService $workflowExecution)
    {
        $document = Document::with([
            'type',
            'creator',
            'attachments',
            'signatureRequests.signer',
            'workflow.steps',
            'statusLogs.user', // ← cargar historial
        ])->findOrFail($id);

        $user = auth::user();
        $this->ensureCanViewDocument($user, $document);

        // Solo mostrar firmas si el documento NO está firmado
        $pendingSignatureRequest = $document->signatureRequests
            ->first(fn ($signatureRequest) => $signatureRequest->signer_user_id === $user->id && $signatureRequest->status === 'pending');
        $workflowSignatureStep = $document->workflow?->steps
            ->first(fn ($step) => $step->status === 'active' && $step->action === 'signature');
        $canSignWorkflowStep = $workflowSignatureStep
            && $workflowExecution->userCanHandleStep($workflowSignatureStep, $user);
        $canSign = $document->status !== DocumentStatus::SIGNED
            && ($pendingSignatureRequest || $canSignWorkflowStep || (! $document->workflow && $document->created_by === $user->id));

        $signatures = collect();
        if ($canSign) {
            $signatures = $user->signatures()->active()->get();
        }

        $padesConfigured = app(\App\Services\InternalPadesSigningService::class)->isConfigured();

        return view('documents.show', compact('document', 'signatures', 'canSignWorkflowStep', 'padesConfigured'));
    }

    public function attachmentFile(Request $request, Document $document, DocumentAttachment $attachment)
    {
        abort_unless($attachment->document_id === $document->id, 404);

        $this->ensureCanViewDocument($request->user(), $document);

        $disk = $attachment->storage_disk ?: 'local';
        abort_unless(Storage::disk($disk)->exists($attachment->file_path), 404);

        if ($request->boolean('download')) {
            return Storage::disk($disk)->download(
                $attachment->file_path,
                $attachment->original_name ?: $attachment->file_name
            );
        }

        return response()->file(
            Storage::disk($disk)->path($attachment->file_path),
            ['Content-Type' => $attachment->mime_type ?: 'application/pdf']
        );
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
                Storage::disk($attachment->storage_disk ?? 'local')->delete($attachment->file_path);
                $attachment->delete();
            });

            $document->versions()->each(function ($version) {
                Storage::disk('local')->delete($version->file_path);
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
        PdfSignatureService $pdfSigner,
        WorkflowExecutionService $workflowExecution
    ) {

        $document = Document::findOrFail($id);

        $signatureRequest = $document->signatureRequests()
            ->where('signer_user_id', auth()->id())
            ->where('status', 'pending')
            ->orderBy('sequence')
            ->first();

        $workflowSignatureStep = $document->workflow()
            ->with('steps')
            ->first()?->steps
            ->first(fn ($step) => $step->status === 'active' && $step->action === 'signature');
        $canSignWorkflowStep = $workflowSignatureStep
            && $workflowExecution->userCanHandleStep($workflowSignatureStep, $request->user());

        abort_unless(
            $signatureRequest || $canSignWorkflowStep || (! $document->workflow && $document->created_by === auth()->id()),
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
            'placement' => ['nullable', 'in:first,last,all,specific'],
            'page_number' => ['nullable', 'required_if:placement,specific', 'integer', 'min:1'],
            'orientation' => ['nullable', 'in:horizontal,vertical'],
            'position_mode' => ['nullable', 'in:automatic,manual'],
            'position_x' => ['nullable', 'required_if:position_mode,manual', 'numeric', 'between:0,1'],
            'position_y' => ['nullable', 'required_if:position_mode,manual', 'numeric', 'between:0,1'],
            'position_width' => ['nullable', 'required_if:position_mode,manual', 'numeric', 'between:0.05,1'],
            'position_height' => ['nullable', 'required_if:position_mode,manual', 'numeric', 'between:0.05,1'],
        ]);

        $signatureOptions = [
            'appearance_type' => $request->input('appearance_type', 'signature'),
            'placement' => $request->input('placement', 'last'),
            'page_number' => $request->integer('page_number') ?: null,
            'orientation' => $request->input('orientation', 'horizontal'),
            'position_mode' => $request->input('position_mode', 'automatic'),
            'position' => $request->input('position_mode') === 'manual' ? [
                'x' => (float) $request->input('position_x'),
                'y' => (float) $request->input('position_y'),
                'width' => (float) $request->input('position_width'),
                'height' => (float) $request->input('position_height'),
            ] : null,
            'slot' => 0,
        ];

        if (app(\App\Services\LlamaTimestampService::class)->isConfigured()) {
            $signatureOptions['timestamp_provider'] = 'Llama.pe TSA';
        }

        DB::beginTransaction();

        try {

            $document = Document::query()
                ->whereKey($document->id)
                ->lockForUpdate()
                ->firstOrFail();

            $signatureOptions['slot'] = $document->attachments()
                ->where('is_signed', true)
                ->lockForUpdate()
                ->get()
                ->filter(fn (DocumentAttachment $attachment) => data_get($attachment->signature_options, 'placement', 'last') === $signatureOptions['placement'])
                ->count();

            if ($signatureRequest) {
                $signatureRequest->refresh();
                abort_unless($signatureRequest->status === 'pending', 422, 'Esta solicitud de firma ya fue atendida.');
            }

            // $signature = Signature::findOrFail(
            //     $request->signature_id
            // );

            $signature = Signature::query()
                ->where('id', $request->signature_id)
                ->where('user_id', auth()->id())
                ->where('active', true)
                ->firstOrFail();

            if ($canSignWorkflowStep && $signature->type !== 'official') {
                throw ValidationException::withMessages([
                    'signature_id' => 'La etapa del flujo requiere una firma digital oficial o un visto bueno digital.',
                ]);
            }

            if ($signature->type === 'official'
                && app(\App\Services\InternalPadesSigningService::class)->isConfigured()
                && blank($signature->pfx_password)) {
                $request->validate([
                    'certificate_password' => ['required', 'string'],
                ], [
                    'certificate_password.required' => 'Ingrese la contraseña de su certificado para firmar.',
                ]);
            }

            if (! app(\App\Services\InternalPadesSigningService::class)->isConfigured()
                && $signature->type === 'official' && $document->attachments()
                ->where('is_signed', true)
                ->whereHas('signature', fn ($query) => $query->where('type', 'official'))
                ->exists()) {
                throw ValidationException::withMessages([
                    'signature_id' => 'Este PDF ya tiene una firma oficial. Para conservar todas las firmas verificables, las firmas oficiales múltiples requieren firma PAdES incremental; no se reemplazará la firma existente.',
                ]);
            }

            $attachment = $document->attachments()
                ->whereIn('kind', ['primary', 'signed_copy'])
                ->lockForUpdate()
                ->latest('id')
                ->firstOrFail();

            $signedPath = $pdfSigner->sign(
                $attachment,
                $signature,
                $signatureOptions,
                $request->input('certificate_password')
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
                'storage_disk' => 'local',

                'file_type' => 'application/pdf',

                'mime_type' => 'application/pdf',

                'file_size' => Storage::disk('local')
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

            if ($canSignWorkflowStep) {
                $workflowExecution->completeStep(
                    $workflowSignatureStep,
                    $request->user(),
                    $signatureOptions['appearance_type'] === 'approval' ? 'Visto bueno digital aplicado.' : 'Firma digital aplicada.'
                );
            } else {
                $hasPendingRequests = $document->signatureRequests()
                    ->where('status', 'pending')
                    ->exists();
                $document->update([
                    'status' => $hasPendingRequests ? DocumentStatus::PENDING : DocumentStatus::SIGNED,
                ]);
            }

            DocumentStatusLog::create([

                'document_id' => $document->id,

                'action' => 'signed',

                'description' =>
                ($signatureOptions['appearance_type'] === 'approval'
                    ? 'Visto bueno digital aplicado con '
                    : 'Documento firmado con ') . $this->signatureTypeLabel($signature)
                    . (isset($signatureOptions['timestamp_provider'])
                        ? ' y sello de tiempo Llama.pe.'
                        : '.'),

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
                    $query->orWhereHas('workflow.steps', fn($stepQuery) => $stepQuery->whereIn('responsible_area_id', $areaIds));
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
        $belongsToArea = $document->workflow()
            ->whereHas('steps', fn($query) => $query->whereIn('responsible_area_id', $user->areas()->pluck('areas.id')))
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
