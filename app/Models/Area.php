<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
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
            User::class,
            'area_user',
            'area_id',
            'user_id'
        );
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
