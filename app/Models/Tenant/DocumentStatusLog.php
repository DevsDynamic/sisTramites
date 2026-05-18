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
