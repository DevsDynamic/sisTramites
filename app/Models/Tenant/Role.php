<?php

namespace App\Models\Tenant;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'guard_name',
    ];

    protected string $guard_name = 'tenant';
}