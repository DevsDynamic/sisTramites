<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentWorkflow extends Model
{
    protected $fillable = ['document_id', 'workflow_template_id', 'status', 'current_step_order', 'started_by', 'completed_at'];
    protected $casts = ['completed_at' => 'datetime'];
    public function document() { return $this->belongsTo(Document::class); }
    public function template() { return $this->belongsTo(WorkflowTemplate::class, 'workflow_template_id'); }
    public function steps() { return $this->hasMany(DocumentWorkflowStep::class)->orderBy('step_order'); }
    public function currentStep() { return $this->hasOne(DocumentWorkflowStep::class)->whereColumn('step_order', 'document_workflows.current_step_order'); }
}
