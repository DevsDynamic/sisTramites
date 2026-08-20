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

    public function users()
    {
        return $this->belongsToMany(User::class, 'area_user', 'area_id', 'user_id');
    }

    public function documentSeries()
    {
        return $this->hasMany(DocumentSeries::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function outgoingFlows()
    {
        return $this->hasMany(DocumentFlow::class, 'from_area_id');
    }

    public function incomingFlows()
    {
        return $this->hasMany(DocumentFlow::class, 'to_area_id');
    }

    public function workflowPermissions()
    {
        return $this->hasMany(WorkflowPermission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function canDelete(): bool
    {
        return ! $this->users()->exists()
            && ! $this->documentSeries()->exists()
            && ! $this->documents()->exists()
            && ! $this->outgoingFlows()->exists()
            && ! $this->incomingFlows()->exists()
            && ! $this->workflowPermissions()->exists();
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
