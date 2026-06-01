<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSlaRule extends Model
{
    protected $fillable = [
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