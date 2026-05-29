<?php

namespace App\Models\Tenant;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $fillable = [
        'code',
        'subject',
        'content',
        'document_type_id',
        'area_id',
        'created_by',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'status' => DocumentStatus::class,
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(
            TenantUser::class,
            'created_by'
        );
    }
    public function flows()
    {
        return $this->hasMany(DocumentFlow::class);
    }

    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(
            DocumentStatusLog::class,
            'document_id'
        )->latest();
    }

    public function series()
    {
        return $this->belongsTo(DocumentSeries::class, 'document_type_id', 'document_type_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentFileVersion::class);
    }
}
