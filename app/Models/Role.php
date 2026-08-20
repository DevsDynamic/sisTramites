<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const SYSTEM_ROLE = 'Administrador';

    protected $fillable = [
        'name',
        'guard_name',
    ];

    public function isSystem(): bool
    {
        return $this->name === self::SYSTEM_ROLE;
    }

    public function canDelete(): bool
    {
        return ! $this->isSystem() && ! $this->users()->exists();
    }
}
