<?php

namespace App\Models;

use Carbon\CarbonImmutable;
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

    protected $hidden = [
        'pfx_password',
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

    public function signedAttachments()
    {
        return $this->hasMany(
            DocumentAttachment::class,
            'signature_id'
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

    public function certificateExpiresAt(): ?CarbonImmutable
    {
        $timestamp = data_get(
            $this->certificate_data,
            'validTo_time_t'
        );

        return $timestamp
            ? CarbonImmutable::createFromTimestampUTC((int) $timestamp)
            : null;
    }

    public function isExpired(): bool
    {
        $expiresAt = $this->certificateExpiresAt();

        return $expiresAt !== null && $expiresAt->isPast();
    }

    public function getDisplayNameAttribute(): string
    {
        $type = $this->type === 'official'
            ? 'Certificado digital'
            : 'Firma visual';

        return "{$type} {$this->display_code} · {$this->user?->name}";
    }

    public function getDisplayCodeAttribute(): string
    {
        return 'FIR-' . str_pad(
            (string) $this->id,
            6,
            '0',
            STR_PAD_LEFT
        );
    }

    public function canDelete(): bool
    {
        return ! $this->signedAttachments()->exists();
    }

    public function canDeactivate(): bool
    {
        return $this->active;
    }

    public function canActivate(): bool
    {
        return ! $this->active;
    }
}
