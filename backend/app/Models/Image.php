<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'imageable_type',
        'imageable_id',
        'path',
        'type',
        'order',
    ];

    /**
     * Get the parent imageable model (accommodation or room)
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full URL for the image
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}
