<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'description',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'main_image',
        'average_rating',
        'total_reviews',
        'is_active',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'average_rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the owner/manager of the accommodation
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all rooms for this accommodation
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Get active rooms only
     */
    public function activeRooms(): HasMany
    {
        return $this->hasMany(Room::class)->where('is_active', true);
    }

    /**
     * Get amenities for this accommodation
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class)->withTimestamps();
    }

    /**
     * Get images for this accommodation
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable')->orderBy('order');
    }

    /**
     * Get bookings for this accommodation
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get reviews for this accommodation
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get visible reviews only
     */
    public function visibleReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_visible', true);
    }

    /**
     * Get average rating from reviews
     */
    public function getAverageRating(): float
    {
        return round($this->visibleReviews()->avg('rating') ?? 0, 1);
    }

    /**
     * Get review count
     */
    public function getReviewCount(): int
    {
        return $this->visibleReviews()->count();
    }

    /**
     * Get rating distribution
     */
    public function getRatingDistribution(): array
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $distribution[$i] = $this->visibleReviews()
                ->where('rating', '>=', $i)
                ->where('rating', '<', $i + 1)
                ->count();
        }
        return $distribution;
    }

    /**
     * Get the minimum room price
     */
    public function getMinPriceAttribute(): float
    {
        return $this->activeRooms()->min('base_price') ?? 0;
    }

    /**
     * Scope a query to only include active accommodations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to search by location
     */
    public function scopeSearchByCity($query, $city)
    {
        if (!$city) {
            return $query;
        }

        return $query->where('city', 'like', "%{$city}%");
    }

    /**
     * Scope to filter by amenities
     */
    public function scopeWithAmenities($query, array $amenityIds)
    {
        if (empty($amenityIds)) {
            return $query;
        }

        return $query->whereHas('amenities', function ($q) use ($amenityIds) {
            $q->whereIn('amenities.id', $amenityIds);
        }, '=', count($amenityIds));
    }

    /**
     * Scope to filter by minimum rating
     */
    public function scopeMinRating($query, $rating)
    {
        if (!$rating) {
            return $query;
        }

        return $query->where('average_rating', '>=', $rating);
    }

    /**
     * Scope to filter by price range
     */
    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        return $query->whereHas('activeRooms', function ($q) use ($minPrice, $maxPrice) {
            if ($minPrice) {
                $q->where('base_price', '>=', $minPrice);
            }
            if ($maxPrice) {
                $q->where('base_price', '<=', $maxPrice);
            }
        });
    }
}
