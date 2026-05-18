<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DocumentSlaRule extends Model
{
    protected $connection = 'tenant';
    
    protected $fillable = [
        'tenant_id',
        'document_type_id',
        'hours_limit',
        'warning_before_hours',
        'allow_escalation',
    ];

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}