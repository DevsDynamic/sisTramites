<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'max_users',
        'max_signatures',
        'max_documents',
        'active',
    ];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
