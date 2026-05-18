<?php

namespace App\Models\Tenant;

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
        'sent_at',
        'tenant_id'
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function flows()
    {
        return $this->hasMany(DocumentFlow::class);
    }

    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function logs()
    {
        return $this->hasMany(DocumentStatusLog::class);
    }

    public function series()
    {
        return $this->belongsTo(DocumentSeries::class, 'document_type_id', 'document_type_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentFileVersion::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (tenant_id()) {
                $model->tenant_id = tenant_id();
            }
        });

        static::addGlobalScope('tenant', function ($query) {
            if (tenant_id()) {
                $query->where('tenant_id', tenant_id());
            }
        });
    }
}
