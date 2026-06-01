<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'company_name',
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
}