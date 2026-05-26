<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStatusLog extends Model
{
    use HasFactory;
    
    protected $connection = 'tenant';
    
    protected $fillable = [
        'document_id',
        'action',
        'description',
        'user_id',
        'tenant_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
