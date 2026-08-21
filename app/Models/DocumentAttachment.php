<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAttachment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'document_id',
        'signature_id',
        'file_name',
        'original_name',
        'file_path',
        'storage_disk',
        'file_type',
        'mime_type',
        'file_size',
        'is_signed',
        'kind',
        'source_attachment_id',
        'signature_options',
        'uploaded_by'
    ];

    protected $casts = [
        'is_signed' => 'boolean',
        'file_size' => 'integer',
        'signature_options' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    public function sourceAttachment()
    {
        return $this->belongsTo(self::class, 'source_attachment_id');
    }

    public function derivedAttachments()
    {
        return $this->hasMany(self::class, 'source_attachment_id');
    }
}
