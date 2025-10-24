<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'accommodation_id',
        'title',
        'content',
        'rating',
        'cleanliness_rating',
        'service_rating',
        'location_rating',
        'value_rating',
        'photos',
        'helpful_count',
        'is_visible',
        'hidden_at',
        'hidden_reason',
        'response',
        'responded_at',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'cleanliness_rating' => 'decimal:1',
        'service_rating' => 'decimal:1',
        'location_rating' => 'decimal:1',
        'value_rating' => 'decimal:1',
        'photos' => 'array',
        'is_visible' => 'boolean',
        'hidden_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function helpfulVotes()
    {
        return $this->belongsToMany(User::class, 'review_helpfulness')
            ->withTimestamps();
    }

    /**
     * Check if user can write review for this booking
     */
    public static function canWriteReview(Booking $booking): bool
    {
        // Must be checked out
        if ($booking->status->value !== 'checked_out') {
            return false;
        }

        // No existing review
        if (self::where('booking_id', $booking->id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Check if current user found this review helpful
     */
    public function isHelpfulByCurrentUser(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->helpfulVotes()->where('user_id', Auth::id())->exists();
    }

    /**
     * Toggle helpful vote from current user
     */
    public function toggleHelpful(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        $userId = Auth::id();

        if ($this->helpfulVotes()->where('user_id', $userId)->exists()) {
            // Remove vote
            $this->helpfulVotes()->detach($userId);
            $this->decrement('helpful_count');
            return false;
        } else {
            // Add vote
            $this->helpfulVotes()->attach($userId);
            $this->increment('helpful_count');
            return true;
        }
    }

    /**
     * Hide review (admin action)
     */
    public function hide(string $reason): bool
    {
        $this->is_visible = false;
        $this->hidden_at = now();
        $this->hidden_reason = $reason;

        return $this->save();
    }

    /**
     * Unhide review (admin action)
     */
    public function unhide(): bool
    {
        $this->is_visible = true;
        $this->hidden_at = null;
        $this->hidden_reason = null;

        return $this->save();
    }

    /**
     * Add response from accommodation
     */
    public function addResponse(string $response): bool
    {
        $this->response = $response;
        $this->responded_at = now();

        return $this->save();
    }

    /**
     * Get average category rating
     */
    public function getAverageCategoryRating(): ?float
    {
        $ratings = array_filter([
            $this->cleanliness_rating,
            $this->service_rating,
            $this->location_rating,
            $this->value_rating,
        ]);

        if (empty($ratings)) {
            return null;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }

    /**
     * Get star display (filled, half, empty)
     */
    public function getStarDisplay(): array
    {
        $rating = $this->rating;
        $fullStars = floor($rating);
        $hasHalfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

        return [
            'full' => $fullStars,
            'half' => $hasHalfStar ? 1 : 0,
            'empty' => $emptyStars,
        ];
    }

    /**
     * Scope: Get visible reviews
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope: Get reviews by rating
     */
    public function scopeByRating($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Scope: Order by most helpful
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    /**
     * Scope: Order by newest
     */
    public function scopeNewest($query)
    {
        return $query->latest();
    }

    /**
     * Scope: With photos only
     */
    public function scopeWithPhotos($query)
    {
        return $query->whereNotNull('photos');
    }

    /**
     * Scope: For accommodation
     */
    public function scopeForAccommodation($query, $accommodationId)
    {
        return $query->where('accommodation_id', $accommodationId);
    }
}
