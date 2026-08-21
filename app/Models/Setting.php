<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
        'plan_name',
        'plan_id',
        'license_starts_at',
        'license_cycle',
        'license_custom_days',
        'license_expires_at',
        'logo',
        'favicon',
        'login_background',
        'primary_color',
        'sidebar_color',
        'sidebar_text_color',
        'email',
        'phone',
        'website',
        'address',
        'onboarding_completed',
    ];

    protected $casts = [
        'license_starts_at' => 'date',
        'license_expires_at' => 'date',
        'onboarding_completed' => 'boolean',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
