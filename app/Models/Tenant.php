<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant
{
    use HasDomains;

    protected $fillable = [
        'id',
        'ruc',
        'razon_social',
        'email',
        'plan_id',
        'starts_at',
        'ends_at',
        'status',            // active, suspended
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
