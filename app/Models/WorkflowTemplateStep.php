<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTemplateStep extends Model
{
    protected $fillable = ['workflow_template_id', 'step_order', 'name', 'action', 'responsible_area_id', 'responsible_role_id', 'requires_signature', 'sla_hours', 'warning_before_hours'];
    protected $casts = ['requires_signature' => 'boolean', 'sla_hours' => 'integer', 'warning_before_hours' => 'integer'];
    public function template() { return $this->belongsTo(WorkflowTemplate::class, 'workflow_template_id'); }
    public function responsibleArea() { return $this->belongsTo(Area::class, 'responsible_area_id'); }
    public function responsibleRole() { return $this->belongsTo(Role::class, 'responsible_role_id'); }
}
