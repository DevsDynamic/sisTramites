<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSeries extends Model
{
    use HasFactory;
    
    protected $connection = 'tenant';
    
    protected $fillable = [
        'document_type_id',
        'area_id',
        'prefix',
        'current_number',
        'padding',
        'reset_yearly',
        'tenant_id',
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
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

    // 🔥 genera correlativo (base simple, luego lo hacemos service pro)
    public function generateCode()
    {
        $this->current_number++;

        return $this->prefix . '-' . str_pad($this->current_number, $this->padding, '0', STR_PAD_LEFT);
    }
}
