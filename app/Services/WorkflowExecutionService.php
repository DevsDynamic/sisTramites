<?php

namespace App\Services;

use App\Enums\DocumentStatus;
use App\Models\Document;
use App\Models\DocumentStatusLog;
use App\Models\DocumentWorkflow;
use App\Models\DocumentWorkflowStep;
use App\Models\User;
use App\Models\WorkflowTemplate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkflowExecutionService
{
    public function __construct(private NotificationService $notifications) {}

    public function start(Document $document, WorkflowTemplate $template, User $user): DocumentWorkflow
    {
        if (! $template->active || $template->steps()->doesntExist()) {
            throw ValidationException::withMessages([
                'workflow_template_id' => 'El flujo seleccionado no está activo o no tiene etapas configuradas.',
            ]);
        }

        if (($template->document_type_id && $template->document_type_id !== $document->document_type_id)
            || ($template->origin_area_id && $template->origin_area_id !== $document->area_id)) {
            throw ValidationException::withMessages([
                'workflow_template_id' => 'El flujo seleccionado no corresponde al tipo o área del documento.',
            ]);
        }

        return DB::transaction(function () use ($document, $template, $user) {
            $workflow = DocumentWorkflow::create([
                'document_id' => $document->id,
                'workflow_template_id' => $template->id,
                'started_by' => $user->id,
                'status' => 'active',
                'current_step_order' => 1,
            ]);

            foreach ($template->steps as $step) {
                $dueAt = $step->sla_hours ? now()->addHours($step->sla_hours) : null;
                $warningAt = $dueAt
                    ? $dueAt->copy()->subHours(min($step->warning_before_hours ?: 0, $step->sla_hours))
                    : null;

                $workflow->steps()->create([
                    'workflow_template_step_id' => $step->id,
                    'step_order' => $step->step_order,
                    'name' => $step->name,
                    'action' => $step->action,
                    'responsible_area_id' => $step->responsible_area_id,
                    'responsible_role_id' => $step->responsible_role_id,
                    'requires_signature' => $step->requires_signature,
                    'status' => $step->step_order === 1 ? 'active' : 'pending',
                    'due_at' => $step->step_order === 1 ? $dueAt : null,
                    'warning_at' => $step->step_order === 1 ? $warningAt : null,
                ]);
            }

            $document->update(['status' => DocumentStatus::IN_PROCESS]);
            DocumentStatusLog::create([
                'document_id' => $document->id,
                'action' => 'workflow_started',
                'description' => 'Flujo iniciado: ' . $template->name,
                'user_id' => $user->id,
            ]);

            $this->notifyCurrentStep($workflow);

            return $workflow;
        });
    }

    public function notifyStep(DocumentWorkflowStep $step, string $type = 'workflow_pending', ?string $message = null): void
    {
        if (! $step || ! $step->responsible_area_id) return;

        User::active()->whereHas('areas', fn($query) => $query->whereKey($step->responsible_area_id))
            ->when($step->responsible_role_id, fn($query) => $query->whereHas('roles', fn($roles) => $roles->whereKey($step->responsible_role_id)))
            ->each(fn(User $user) => $this->notifications->send(
                $user->id,
                $type,
                $message ?: 'Tiene una etapa pendiente: ' . $step->name,
                $step->workflow->document_id
            ));
    }

    private function notifyCurrentStep(DocumentWorkflow $workflow): void
    {
        $step = $workflow->steps()->where('status', 'active')->first();
        if (! $step) return;

        $this->notifyStep($step);
    }

    public function userCanHandleStep(DocumentWorkflowStep $step, User $user): bool
    {
        return $user->areas()->whereKey($step->responsible_area_id)->exists()
            && (! $step->responsible_role_id || $user->roles()->whereKey($step->responsible_role_id)->exists());
    }

    public function completeStep(DocumentWorkflowStep $step, User $user, ?string $comment = null): void
    {
        abort_unless($step->status === 'active', 422, 'Esta etapa ya fue atendida.');
        abort_unless($this->userCanHandleStep($step, $user), 403);

        $step->update([
            'status' => 'completed',
            'acted_by' => $user->id,
            'acted_at' => now(),
            'comment' => $comment,
        ]);

        $workflow = $step->workflow;
        $next = $workflow->steps()->where('step_order', $step->step_order + 1)->first();

        if ($next) {
            $templateStep = $next->workflowTemplateStep;
            $dueAt = $templateStep?->sla_hours ? now()->addHours($templateStep->sla_hours) : null;
            $warningAt = $dueAt
                ? $dueAt->copy()->subHours(min($templateStep->warning_before_hours ?: 0, $templateStep->sla_hours))
                : null;

            $next->update([
                'status' => 'active',
                'due_at' => $dueAt,
                'warning_at' => $warningAt,
                'warning_sent_at' => null,
                'overdue_at' => null,
            ]);
            $workflow->update(['current_step_order' => $next->step_order]);
            $this->notifyCurrentStep($workflow->fresh());
        } else {
            $workflow->update(['status' => 'completed', 'completed_at' => now()]);
            $workflow->document->update(['status' => DocumentStatus::APPROVED]);
        }

        DocumentStatusLog::create([
            'document_id' => $workflow->document_id,
            'action' => 'workflow_step_completed',
            'description' => 'Etapa completada: ' . $step->name,
            'user_id' => $user->id,
        ]);
    }
}
