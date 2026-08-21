<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentWorkflowStep extends Model
{
    protected $fillable = ['document_workflow_id', 'workflow_template_step_id', 'step_order', 'name', 'action', 'responsible_area_id', 'responsible_role_id', 'requires_signature', 'status', 'acted_by', 'comment', 'acted_at', 'due_at', 'warning_at', 'warning_sent_at', 'overdue_at'];
    protected $casts = ['requires_signature' => 'boolean', 'acted_at' => 'datetime', 'due_at' => 'datetime', 'warning_at' => 'datetime', 'warning_sent_at' => 'datetime', 'overdue_at' => 'datetime'];
    public function workflow() { return $this->belongsTo(DocumentWorkflow::class, 'document_workflow_id'); }
    public function workflowTemplateStep() { return $this->belongsTo(WorkflowTemplateStep::class, 'workflow_template_step_id'); }
    public function responsibleArea() { return $this->belongsTo(Area::class, 'responsible_area_id'); }
    public function responsibleRole() { return $this->belongsTo(Role::class, 'responsible_role_id'); }
    public function actor() { return $this->belongsTo(User::class, 'acted_by'); }
}
