<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentSignatureRequest extends Model
{
    protected $fillable = [
        'document_id',
        'signer_user_id',
        'requested_by',
        'signature_id',
        'signed_attachment_id',
        'sequence',
        'status',
        'signed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function signer()
    {
        return $this->belongsTo(User::class, 'signer_user_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    public function signedAttachment()
    {
        return $this->belongsTo(DocumentAttachment::class, 'signed_attachment_id');
    }
}
