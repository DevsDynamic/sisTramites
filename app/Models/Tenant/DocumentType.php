<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'code',
        'allow_image_signature',
        'require_digital_signature',
        'active',
    ];

    public function series()
    {
        return $this->hasMany(DocumentSeries::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
