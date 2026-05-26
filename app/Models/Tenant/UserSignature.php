<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class UserSignature extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'user_id',
        'type',
        'pfx_path',
        'pfx_password',
        'image_path',
        'is_default',
        'active',
        'expires_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'is_default' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /* RELATIONS */
    public function user()
    {
        return $this->belongsTo(TenantUser::class);
    }

    /* HELPERS */
    public function isDigital(): bool
    {
        return $this->type === 'digital';
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isExpired(): bool
    {
        return $this->expires_at
            && now()->gt($this->expires_at);
    }
}
