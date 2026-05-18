<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Area extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'code',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
    /* USERS */
    public function users()
    {
        return $this->belongsToMany(
            TenantUser::class,
            'area_user',
            'area_id',
            'user_id'
        );
    }
}
