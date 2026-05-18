<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $connection = 'tenant';
    
    protected $fillable = [
        'tenant_id',
        'user_id',
        'type',
        'message',
        'document_id',
        'read'
    ];
}
