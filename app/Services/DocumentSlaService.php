<?php

namespace App\Services;

use App\Models\DocumentFlow;
use App\Models\DocumentSlaRule;
use Carbon\Carbon;

class DocumentSlaService
{
    /**
     * ASIGNAR SLA AL FLUJO
     */
    public function assign(DocumentFlow $flow)
    {
        $rule = DocumentSlaRule::where(
            'document_type_id',
            $flow->document->document_type_id
        )->first();

        if (!$rule) {
            return;
        }

        $deadline = Carbon::now()
            ->addHours($rule->hours_limit);

        $flow->update([
            'sla_deadline' => $deadline,
        ]);
    }

    /**
     * VERIFICAR SI VENCIÓ
     */
    public function isExpired(DocumentFlow $flow): bool
    {
        if (!$flow->sla_deadline) {
            return false;
        }

        return now()->greaterThan($flow->sla_deadline);
    }

    /**
     * HORAS RESTANTES
     */
    public function remainingHours(DocumentFlow $flow): int
    {
        if (!$flow->sla_deadline) {
            return 0;
        }

        return now()->diffInHours(
            $flow->sla_deadline,
            false
        );
    }
}