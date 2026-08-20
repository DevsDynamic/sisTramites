<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'subject',
        'content',
        'document_type_id',
        'document_series_id',
        'area_id',
        'created_by',
        'status',
        'sent_at'
    ];

    protected $casts = [
        'status' => DocumentStatus::class,
    ];

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
    public function flows()
    {
        return $this->hasMany(DocumentFlow::class);
    }

    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(
            DocumentStatusLog::class,
            'document_id'
        )->latest();
    }

    public function series()
    {
        return $this->belongsTo(DocumentSeries::class);
    }

    public function versions()
    {
        return $this->hasMany(DocumentFileVersion::class);
    }

    public function signatureRequests()
    {
        return $this->hasMany(DocumentSignatureRequest::class)->orderBy('sequence');
    }

    public function canEdit(): bool
    {
        return $this->status === DocumentStatus::DRAFT
            && ! $this->flows()->exists()
            && ! $this->attachments()->where('is_signed', true)->exists();
    }

    public function canDelete(): bool
    {
        return $this->canEdit();
    }
}
