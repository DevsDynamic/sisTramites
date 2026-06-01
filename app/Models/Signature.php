<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    protected $fillable = [

        'user_id',
        'type',
        'pfx_path',
        'signature_image',
        'pfx_password',
        'certificate_data',
        'is_default',
        'active',
    ];

    protected $casts = [

        'certificate_data' => 'array',
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
            User::class,
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
