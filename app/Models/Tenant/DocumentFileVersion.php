<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DocumentFileVersion extends Model
{
    protected $connection = 'tenant';
    
    protected $fillable = [
        'tenant_id',
        'document_id',
        'uploaded_by',
        'version',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'comment',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(TenantUser::class, 'uploaded_by');
    }
}
