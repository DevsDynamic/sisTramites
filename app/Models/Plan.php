<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'max_users',
        'max_documents',
        'max_signatures',
        'is_trial'
    ];
}
