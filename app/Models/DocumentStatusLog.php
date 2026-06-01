<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentStatusLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'document_id',
        'action',
        'description',
        'user_id'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
