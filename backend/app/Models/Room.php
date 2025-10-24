<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'name',
        'description',
        'max_occupancy',
        'size',
        'bed_type',
        'bed_count',
        'base_price',
        'total_rooms',
        'main_image',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the accommodation that owns the room
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    /**
     * Get amenities for this room
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class)->withTimestamps();
    }

    /**
     * Get images for this room
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('order');
    }

    /**
     * Scope a query to only include active rooms
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check availability for a date range
     * TODO: Implement with bookings table in Epic 3
     */
    public function checkAvailability($checkIn, $checkOut): int
    {
        // For now, return total rooms
        // Will be implemented properly in Epic 3 with bookings
        return $this->total_rooms;
    }
}
