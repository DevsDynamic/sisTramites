<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentStatusLog;
use Illuminate\Support\Facades\DB;

class DocumentService
{
    public function __construct(
        protected DocumentSeriesService $seriesService,
        protected DocumentFlowService $flowService,
        protected NotificationService $notificationService
    ) {}

    public function create(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {

            /**
             * GENERAR CÓDIGO
             */
            $code = $this->seriesService->generate(
                $data['document_type_id'],
                $user->area_id
            );

            /**
             * CREAR DOCUMENTO
             */
            $document = Document::create([
                'code' => $code,
                'subject' => $data['subject'],
                'content' => $data['content'] ?? null,
                'document_type_id' => $data['document_type_id'],
                'area_id' => $user->area_id,
                'created_by' => $user->id,
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            /**
             * INICIAR FLUJO
             */
            $flow = $this->flowService->send(
                $document,
                $data['to_area_id'],
                $user
            );

            /**
             * NOTIFICACIÓN
             */
            if (!empty($data['to_user_id'])) {

                $this->notificationService->send(
                    userId: $data['to_user_id'],
                    type: 'document_created',
                    message: 'Nuevo documento recibido',
                    documentId: $document->id
                );
            }

            /**
             * LOG
             */
            DocumentStatusLog::create([
                'document_id' => $document->id,
                'action' => 'created',
                'description' => 'Documento creado',
                'user_id' => $user->id,
            ]);

            return $document;
        });
    }
}