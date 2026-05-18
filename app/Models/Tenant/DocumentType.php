<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;
    
    protected $connection = 'tenant';
    
    protected $fillable = [
        'name',
        'code',
        'is_active',
        'tenant_id',
    ];

    public function series()
    {
        return $this->hasMany(DocumentSeries::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // 🔐 MULTITENANT SCOPE (IMPORTANTE)
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
