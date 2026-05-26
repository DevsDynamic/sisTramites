<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [

        'user_id',
        'type',
        'pfx_path',
        'signature_image',
        'pfx_password',
        'issuer',
        'expires_at',
        'is_default',
        'active',
    ];

    protected $casts = [

        'expires_at' => 'date',
        'active' => 'boolean',
        'is_default' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            TenantUser::class,
            'user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
