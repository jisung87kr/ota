<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $this->belongsToMany(Amenity::class, 'room_amenity')->withTimestamps();
    }

    /**
     * Get images for this room
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('order');
    }

    /**
     * Get bookings for this room
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
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
     * Returns the number of available rooms for the given date range
     */
    public function checkAvailability($checkIn, $checkOut): int
    {
        // Convert to Carbon dates if strings
        if (is_string($checkIn)) {
            $checkIn = \Carbon\Carbon::parse($checkIn);
        }
        if (is_string($checkOut)) {
            $checkOut = \Carbon\Carbon::parse($checkOut);
        }

        // Count bookings that overlap with the requested date range
        $bookedRooms = $this->bookings()
            ->whereIn('status', [
                BookingStatus::PENDING->value,
                BookingStatus::CONFIRMED->value,
                BookingStatus::CHECKED_IN->value,
            ])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                          ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->count();

        return max(0, $this->total_rooms - $bookedRooms);
    }

    /**
     * Check if room is available for the given date range
     */
    public function isAvailable($checkIn, $checkOut): bool
    {
        return $this->checkAvailability($checkIn, $checkOut) > 0;
    }
}
