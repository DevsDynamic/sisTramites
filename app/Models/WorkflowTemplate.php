<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowTemplate extends Model
{
    protected $fillable = ['name', 'description', 'document_type_id', 'origin_area_id', 'active', 'created_by'];

    protected $casts = ['active' => 'boolean'];

    public function steps() { return $this->hasMany(WorkflowTemplateStep::class)->orderBy('step_order'); }
    public function documentType() { return $this->belongsTo(DocumentType::class); }
    public function originArea() { return $this->belongsTo(Area::class, 'origin_area_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function scopeActive($query) { return $query->where('active', true); }
}
