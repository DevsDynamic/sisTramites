<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    use HasFactory;
    
    protected $connection = 'tenant';
    
    protected $fillable = [
        'document_id',
        'file_name',
        'file_path',
        'file_type',
        'size',
        'uploaded_by'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
