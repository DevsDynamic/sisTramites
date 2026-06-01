<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'plan_id',
        'amount',
        'status',
        'paid_at'
    ];



    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
