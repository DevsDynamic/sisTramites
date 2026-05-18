<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class WorkflowPermission extends Model
{
    protected $connection = 'tenant';
    
    protected $fillable = [
        'tenant_id',
        'document_type_id',
        'area_id',
        'allowed_actions',
    ];

    protected $casts = [
        'allowed_actions' => 'array',
    ];
}