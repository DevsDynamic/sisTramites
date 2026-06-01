<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowPermission extends Model
{
    protected $fillable = [
        'document_type_id',
        'area_id',
        'allowed_actions',
    ];

    protected $casts = [
        'allowed_actions' => 'array',
    ];
}