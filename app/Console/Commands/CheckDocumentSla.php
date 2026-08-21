<?php

namespace App\Console\Commands;

use App\Models\DocumentWorkflowStep;
use App\Services\WorkflowExecutionService;
use Illuminate\Console\Command;

class CheckDocumentSla extends Command
{
    protected $signature = 'documents:check-sla';

    protected $description = 'Envía alertas de SLA próximo a vencer y vencido para etapas activas.';

    public function handle(WorkflowExecutionService $workflows): int
    {
        $now = now();
        $warnings = DocumentWorkflowStep::with('workflow')
            ->where('status', 'active')
            ->whereNotNull('warning_at')
            ->whereNull('warning_sent_at')
            ->where('warning_at', '<=', $now)
            ->where('due_at', '>', $now)
            ->get();

        foreach ($warnings as $step) {
            $step->update(['warning_sent_at' => $now]);
            $workflows->notifyStep(
                $step,
                'workflow_sla_warning',
                'La etapa "' . $step->name . '" está próxima a vencer (' . $step->due_at->format('d/m/Y H:i') . ').'
            );
        }

        $overdue = DocumentWorkflowStep::with('workflow')
            ->where('status', 'active')
            ->whereNotNull('due_at')
            ->whereNull('overdue_at')
            ->where('due_at', '<=', $now)
            ->get();

        foreach ($overdue as $step) {
            $step->update(['overdue_at' => $now]);
            $workflows->notifyStep(
                $step,
                'workflow_sla_overdue',
                'La etapa "' . $step->name . '" venció el ' . $step->due_at->format('d/m/Y H:i') . '.'
            );
        }

        $this->info("SLA revisado: {$warnings->count()} alertas y {$overdue->count()} vencimientos.");

        return self::SUCCESS;
    }
}
