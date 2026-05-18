<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Domain;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /* DESACTIVAR COLUMNA JSON DATA */
    protected $dataColumn = null;

    /* CUSTOM COLUMNS */
    protected $fillable = [
        'id',
        'business_name',
        'trade_name',
        'ruc',
        'email',
        'phone',
        'plan_id',
        'status',
        'starts_at',
        'expires_at',
        'settings',
        'onboarding_completed',
        'sunat_user',
        'sunat_password',
    ];

    protected $casts = [
        'settings' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'business_name',
            'trade_name',
            'ruc',
            'email',
            'phone',
            'plan_id',
            'status',
            'starts_at',
            'expires_at',
            'settings',
            'onboarding_completed',
            'sunat_user',
            'sunat_password',
            'created_at',
            'updated_at',
        ];
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isExpired(): bool
    {
        return $this->expires_at
            && now()->gt($this->expires_at);
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
