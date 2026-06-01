<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentFlow;
use Illuminate\Support\Facades\DB;

class DocumentFlowService
{
    public function __construct(
        protected DocumentSlaService $slaService
    ) {}

    public function send(
        Document $document,
        int $toAreaId,
        $user
    ) {

        return DB::transaction(function () use (
            $document,
            $toAreaId,
            $user
        ) {

            /**
             * CREAR FLUJO
             */
            $flow = DocumentFlow::create([
                'document_id' => $document->id,
                'from_area_id' => $user->area_id,
                'to_area_id' => $toAreaId,
                'sent_by' => $user->id,
                'status' => 'pending',
                'sent_at' => now(),
            ]);

            /**
             * ASIGNAR SLA
             */
            $this->slaService->assign($flow);

            /**
             * ACTUALIZAR DOCUMENTO
             */
            $document->update([
                'status' => 'in_process',
            ]);

            return $flow;
        });
    }
}
