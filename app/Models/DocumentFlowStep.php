<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFlowStep extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'document_type_id',
        'from_area_id',
        'to_area_id',
        'order',
        'is_required',
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
}
