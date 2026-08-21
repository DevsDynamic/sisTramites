<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workflow_template_steps', function (Blueprint $table) {
            $table->unsignedInteger('sla_hours')->nullable()->after('requires_signature');
            $table->unsignedInteger('warning_before_hours')->nullable()->after('sla_hours');
        });

        Schema::table('document_workflow_steps', function (Blueprint $table) {
            $table->timestamp('due_at')->nullable()->after('acted_at');
            $table->timestamp('warning_at')->nullable()->after('due_at');
            $table->timestamp('warning_sent_at')->nullable()->after('warning_at');
            $table->timestamp('overdue_at')->nullable()->after('warning_sent_at');
            $table->index(['status', 'due_at']);
        });

        if (! Schema::hasTable('document_flows')) {
            return;
        }

        DB::table('document_flows')
            ->orderBy('document_id')
            ->orderBy('sent_at')
            ->orderBy('id')
            ->get()
            ->groupBy('document_id')
            ->each(function ($legacyFlows, $documentId): void {
                // A document can have only one canonical workflow. Do not overwrite
                // workflows that were already created with the new engine.
                if (DB::table('document_workflows')->where('document_id', $documentId)->exists()) {
                    return;
                }

                $first = $legacyFlows->first();
                $startedBy = DB::table('users')->where('id', $first->sent_by)->exists()
                    ? $first->sent_by
                    : DB::table('documents')->where('id', $documentId)->value('created_by');

                if (! $startedBy || ! DB::table('users')->where('id', $startedBy)->exists()) {
                    return;
                }

                $last = $legacyFlows->last();
                $workflowStatus = match ($last->status) {
                    'approved' => 'completed',
                    'rejected' => 'rejected',
                    default => 'active',
                };
                $currentOrder = $legacyFlows->count();
                $createdAt = $first->sent_at ?: $first->created_at ?: now();
                $completedAt = in_array($workflowStatus, ['completed', 'rejected'], true)
                    ? ($last->received_at ?: $last->updated_at)
                    : null;

                $workflowId = DB::table('document_workflows')->insertGetId([
                    'document_id' => $documentId,
                    'workflow_template_id' => null,
                    'status' => $workflowStatus,
                    'current_step_order' => $currentOrder,
                    'started_by' => $startedBy,
                    'completed_at' => $completedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $last->updated_at ?: $createdAt,
                ]);

                foreach ($legacyFlows->values() as $index => $legacy) {
                    $order = $index + 1;
                    $isLast = $order === $currentOrder;
                    $stepStatus = match ($legacy->status) {
                        'approved', 'received' => 'completed',
                        'rejected' => 'rejected',
                        'observed' => $isLast ? 'active' : 'completed',
                        default => $isLast ? 'active' : 'completed',
                    };

                    DB::table('document_workflow_steps')->insert([
                        'document_workflow_id' => $workflowId,
                        'workflow_template_step_id' => null,
                        'step_order' => $order,
                        'name' => 'Etapa migrada ' . $order,
                        'action' => 'approval',
                        'responsible_area_id' => DB::table('areas')->where('id', $legacy->to_area_id)->exists() ? $legacy->to_area_id : null,
                        'responsible_role_id' => null,
                        'requires_signature' => false,
                        'status' => $stepStatus,
                        'acted_by' => $legacy->received_by && DB::table('users')->where('id', $legacy->received_by)->exists() ? $legacy->received_by : null,
                        'comment' => $legacy->comment,
                        'acted_at' => $legacy->received_at,
                        'due_at' => $legacy->sla_deadline,
                        'warning_at' => $legacy->sla_deadline,
                        'warning_sent_at' => $legacy->sla_warning_sent ? ($legacy->updated_at ?: now()) : null,
                        'overdue_at' => $legacy->sla_expired ? ($legacy->sla_escalated_at ?: $legacy->updated_at ?: now()) : null,
                        'created_at' => $legacy->sent_at ?: $legacy->created_at ?: now(),
                        'updated_at' => $legacy->updated_at ?: $legacy->sent_at ?: now(),
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('document_workflow_steps', function (Blueprint $table) {
            $table->dropIndex(['status', 'due_at']);
            $table->dropColumn(['due_at', 'warning_at', 'warning_sent_at', 'overdue_at']);
        });

        Schema::table('workflow_template_steps', function (Blueprint $table) {
            $table->dropColumn(['sla_hours', 'warning_before_hours']);
        });
    }
};
