<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentSeries extends Model
{
    use HasFactory;

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

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('prefix', 'like', "%{$search}%")
                ->orWhereHas('documentType', function ($sub) use ($search) {
                    $sub->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });
        });
    }

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

    public function previewCode(): string
    {
        return $this->prefix . '-' . str_pad(
            $this->current_number + 1,
            $this->padding,
            '0',
            STR_PAD_LEFT
        );
    }

    // 🔥 genera correlativo (base simple, luego lo hacemos service pro)
    public function generateCode()
    {
        $this->current_number++;

        return $this->prefix . '-' . str_pad($this->current_number, $this->padding, '0', STR_PAD_LEFT);
    }
}
