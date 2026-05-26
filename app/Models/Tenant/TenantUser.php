<?php

namespace App\Models\Tenant;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class TenantUser extends Authenticatable
{
    use HasRoles, Notifiable;
    // protected $connection = 'tenant';
    protected $table = 'users';
    protected $guard_name = 'tenant';

    protected $fillable = [
        'name',
        'email',
        'password',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'last_seen_at' => 'datetime',
    ];

    public function areas()
    {
        return $this->belongsToMany(
            Area::class,
            'area_user',
            'user_id',
            'area_id'
        );
    }

    public function isOnline(): bool
    {
        if (!$this->last_seen_at) {
            return false;
        }

        return $this->last_seen_at
            ->gt(now()->subMinutes(5));
    }

    public function signatures()
    {
        return $this->hasMany(
            UserSignature::class,
            'user_id'
        );
    }

    public function defaultSignature()
    {
        return $this->hasOne(
            UserSignature::class,
            'user_id'
        )->where('is_default', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
