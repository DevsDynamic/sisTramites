<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSeries extends Model
{
    protected $fillable = [
        'document_type_id',
        'area_id',
        'prefix',
        'current_number',
        'padding',
        'reset_yearly',
        'active',
    ];

    protected $casts = [
        'current_number' => 'integer',
        'padding' => 'integer',
        'reset_yearly' => 'boolean',
        'active' => 'boolean',
    ];

    public function documentType()
    {
        return $this->belongsTo(
            DocumentType::class,
            'document_type_id'
        );
    }

    public function area()
    {
        return $this->belongsTo(
            Area::class
        );
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function previewCode(): string
    {
        return $this->prefix . '-' . str_pad(
            $this->current_number + 1,
            $this->padding,
            '0',
            STR_PAD_LEFT
        );
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function canDelete(): bool
    {
        return ! $this->documents()->exists();
    }

    public function canDeactivate(): bool
    {
        return $this->active;
    }

    public function canActivate(): bool
    {
        return !$this->active;
    }
}
