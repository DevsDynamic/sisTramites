<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFlow extends Model
{
    use HasFactory;

    protected $connection = 'tenant';
    
    protected $fillable = [
        'document_id',
        'from_area_id',
        'to_area_id',
        'sent_by',
        'received_by',
        'comment',
        'status',
        'sent_at',
        'received_at',
        'tenant_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function getSlaBadgeAttribute()
    {
        if ($this->sla_expired) {
            return 'danger';
        }

        if (
            $this->sla_deadline &&
            now()->diffInHours($this->sla_deadline, false) <= 4
        ) {
            return 'warning';
        }

        return 'success';
    }
}
