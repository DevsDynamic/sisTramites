<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain as BaseDomain;
use Stancl\Tenancy\Database\Models\Tenant;

class Domain extends BaseDomain
{
    protected $fillable = [
        'domain',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN
    |--------------------------------------------------------------------------
    */

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
