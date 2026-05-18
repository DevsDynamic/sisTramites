<?php

namespace App\Services;

use App\Models\Tenant\DocumentFlow;
use App\Models\Tenant\DocumentStatusLog;
use Illuminate\Support\Facades\DB;

class DocumentActionService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected WorkflowPermissionService $permissionService
    ) {}

    /**
     * RECIBIR
     */
    public function receive(DocumentFlow $flow, $user)
    {
        return DB::transaction(function () use ($flow, $user) {

            $flow->update([
                'status' => 'received',
                'received_by' => $user->id,
                'received_at' => now(),
            ]);

            $this->notify(
                userId: $flow->sent_by,
                type: 'document_received',
                message: 'Tu documento fue recibido',
                documentId: $flow->document_id
            );

            $this->log(
                $flow->document_id,
                'received',
                'Documento recibido',
                $user
            );

            return true;
        });
    }

    /**
     * APROBAR
     */
    public function approve(DocumentFlow $flow, $user)
    {
        return DB::transaction(function () use ($flow, $user) {

            if (!$this->permissionService->can(
                $flow->document->document_type_id,
                $user->area_id,
                'approve'
            )) {

                abort(403, 'No autorizado');
            }

            $flow->update([
                'status' => 'approved',
                'received_by' => $user->id,
                'received_at' => now(),
            ]);

            $flow->document->update([
                'status' => 'approved',
            ]);

            $this->notify(
                userId: $flow->document->created_by,
                type: 'document_approved',
                message: 'Tu documento fue aprobado',
                documentId: $flow->document_id
            );

            $this->log(
                $flow->document_id,
                'approved',
                'Documento aprobado',
                $user
            );

            return true;
        });
    }

    /**
     * RECHAZAR
     */
    public function reject(DocumentFlow $flow, $user, ?string $reason = null)
    {
        return DB::transaction(function () use ($flow, $user, $reason) {

            if (!$this->permissionService->can(
                $flow->document->document_type_id,
                $user->area_id,
                'reject'
            )) {
                abort(403, 'No autorizado');
            }

            $flow->update([
                'status' => 'rejected',
                'comment' => $reason,
                'received_by' => $user->id,
                'received_at' => now(),
            ]);

            $flow->document->update([
                'status' => 'rejected',
            ]);

            $this->notify(
                userId: $flow->document->created_by,
                type: 'document_rejected',
                message: 'Tu documento fue rechazado',
                documentId: $flow->document_id
            );

            $this->log(
                $flow->document_id,
                'rejected',
                $reason ?? 'Documento rechazado',
                $user
            );

            return true;
        });
    }

    /**
     * OBSERVAR
     */
    public function observe(DocumentFlow $flow, $user, string $comment)
    {
        return DB::transaction(function () use ($flow, $user, $comment) {

            if (!$this->permissionService->can(
                $flow->document->document_type_id,
                $user->area_id,
                'observe'
            )) {
                abort(403, 'No autorizado');
            }

            $flow->update([
                'status' => 'observed',
                'comment' => $comment,
                'received_by' => $user->id,
                'received_at' => now(),
            ]);

            $this->notify(
                userId: $flow->document->created_by,
                type: 'document_observed',
                message: 'Tu documento fue observado',
                documentId: $flow->document_id
            );

            $this->log(
                $flow->document_id,
                'observed',
                $comment,
                $user
            );

            return true;
        });
    }

    /**
     * DERIVAR
     */
    public function reassign(
        DocumentFlow $flow,
        int $toAreaId,
        $user,
        ?string $comment = null
    ) {
        return DB::transaction(function () use (
            $flow,
            $toAreaId,
            $user,
            $comment
        ) {

            if (!$this->permissionService->can(
                $flow->document->document_type_id,
                $user->area_id,
                'reassign'
            )) {
                abort(403, 'No autorizado');
            }

            $flow->update([
                'status' => 'approved',
                'received_by' => $user->id,
                'received_at' => now(),
                'comment' => $comment,
            ]);

            $newFlow = DocumentFlow::create([
                'document_id' => $flow->document_id,
                'from_area_id' => $user->area_id,
                'to_area_id' => $toAreaId,
                'sent_by' => $user->id,
                'status' => 'pending',
                'sent_at' => now(),
                'tenant_id' => tenant_id(),
            ]);

            $this->notify(
                userId: $flow->document->created_by,
                type: 'document_reassigned',
                message: 'Tu documento fue derivado a otra área',
                documentId: $flow->document_id
            );

            $this->log(
                $flow->document_id,
                'reassigned',
                'Documento derivado',
                $user
            );

            return $newFlow;
        });
    }

    /**
     * NOTIFICACIONES CENTRALIZADAS
     */
    private function notify(
        int $userId,
        string $type,
        string $message,
        int $documentId
    ): void {

        $this->notificationService->send(
            $userId,
            $type,
            $message,
            $documentId
        );
    }

    /**
     * LOG CENTRALIZADO
     */
    private function log(
        int $documentId,
        string $action,
        string $description,
        $user
    ): void {

        DocumentStatusLog::create([
            'document_id' => $documentId,
            'action' => $action,
            'description' => $description,
            'user_id' => $user->id,
            'tenant_id' => tenant_id(),
        ]);
    }
}
