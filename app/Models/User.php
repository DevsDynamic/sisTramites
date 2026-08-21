<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_path',
        'password',
        'active',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen_at' => 'datetime',
            'is_system_owner' => 'boolean',
        ];
    }

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
        return $this->hasMany(Signature::class);
    }

    public function documentsCreated()
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    public function statusLogs()
    {
        return $this->hasMany(DocumentStatusLog::class, 'user_id');
    }

    public function uploadedAttachments()
    {
        return $this->hasMany(DocumentAttachment::class, 'uploaded_by');
    }

    public function fileVersions()
    {
        return $this->hasMany(DocumentFileVersion::class, 'uploaded_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function defaultSignature()
    {
        return $this->hasOne(Signature::class)
            ->where('is_default', true)
            ->where('active', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function isSystemOwner(): bool
    {
        return $this->is_system_owner;
    }

    public function canDelete(): bool
    {
        return ! $this->isSystemOwner()
            && ! $this->documentsCreated()->exists()
            && ! $this->statusLogs()->exists()
            && ! $this->uploadedAttachments()->exists()
            && ! $this->fileVersions()->exists()
            && ! $this->notifications()->exists()
            && ! $this->signatures()->exists();
    }

    public function canDeactivate(): bool
    {
        return $this->active && ! $this->isSystemOwner();
    }

    public function canActivate(): bool
    {
        return ! $this->active;
    }
}
