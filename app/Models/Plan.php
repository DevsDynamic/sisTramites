<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'max_users',
        'max_areas',
        'max_signatures',
        'max_documents',
        'max_workflows',
        'max_storage_mb',
        'features',
        'sort_order',
        'is_custom',
        'active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'is_custom' => 'boolean',
        'active' => 'boolean',
    ];

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
