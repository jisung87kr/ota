<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'user_id',
        'accommodation_id',
        'room_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_requests',
        'check_in_date',
        'check_out_date',
        'nights',
        'guests',
        'room_price',
        'total_price',
        'paid_amount',
        'status',
        'cancelled_at',
        'cancellation_reason',
        'refund_amount',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'room_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'status' => BookingStatus::class,
        'cancelled_at' => 'datetime',
    ];

    /**
     * Boot method to generate booking number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (!$booking->booking_number) {
                $booking->booking_number = self::generateBookingNumber();
            }
        });
    }

    /**
     * Generate unique booking number
     */
    public static function generateBookingNumber(): string
    {
        do {
            $number = 'BK' . date('Ymd') . strtoupper(substr(uniqid(), -6));
        } while (self::where('booking_number', $number)->exists());

        return $number;
    }

    /**
     * Get the user who made the booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the accommodation for this booking
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class);
    }

    /**
     * Get the room for this booking
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Calculate nights between check-in and check-out
     */
    public function calculateNights(): int
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }

    /**
     * Calculate total price
     */
    public function calculateTotalPrice(): float
    {
        return $this->room_price * $this->nights;
    }

    /**
     * Check if booking can be cancelled based on cancellation policy
     */
    public function canBeCancelled(): bool
    {
        if (!$this->status->canBeCancelled()) {
            return false;
        }

        // Already cancelled
        if ($this->status === BookingStatus::CANCELLED) {
            return false;
        }

        return true;
    }

    /**
     * Calculate refund amount based on cancellation policy
     * - 7+ days before check-in: 100% refund
     * - 3-6 days before check-in: 50% refund
     * - Less than 3 days: No refund
     */
    public function calculateRefundAmount(): float
    {
        if (!$this->canBeCancelled()) {
            return 0;
        }

        $now = now();
        $daysUntilCheckIn = $now->diffInDays($this->check_in_date, false);

        if ($daysUntilCheckIn >= 7) {
            return $this->total_price; // 100% refund
        } elseif ($daysUntilCheckIn >= 3) {
            return $this->total_price * 0.5; // 50% refund
        } else {
            return 0; // No refund
        }
    }

    /**
     * Cancel the booking
     */
    public function cancel(string $reason = null): bool
    {
        if (!$this->canBeCancelled()) {
            return false;
        }

        $this->status = BookingStatus::CANCELLED;
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        $this->refund_amount = $this->calculateRefundAmount();

        return $this->save();
    }

    /**
     * Confirm the booking
     */
    public function confirm(): bool
    {
        if ($this->status !== BookingStatus::PENDING) {
            return false;
        }

        $this->status = BookingStatus::CONFIRMED;
        $this->paid_amount = $this->total_price;

        return $this->save();
    }

    /**
     * Check in
     */
    public function checkIn(): bool
    {
        if (!$this->status->canBeCheckedIn()) {
            return false;
        }

        $this->status = BookingStatus::CHECKED_IN;
        return $this->save();
    }

    /**
     * Check out
     */
    public function checkOut(): bool
    {
        if (!$this->status->canBeCheckedOut()) {
            return false;
        }

        $this->status = BookingStatus::CHECKED_OUT;
        return $this->save();
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, BookingStatus|string $status)
    {
        if (is_string($status)) {
            $status = BookingStatus::from($status);
        }

        return $query->where('status', $status->value);
    }

    /**
     * Scope to get upcoming bookings
     */
    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', [BookingStatus::CONFIRMED->value, BookingStatus::CHECKED_IN->value])
            ->where('check_in_date', '>=', now()->toDateString());
    }

    /**
     * Scope to get past bookings
     */
    public function scopePast($query)
    {
        return $query->where('status', BookingStatus::CHECKED_OUT->value)
            ->orWhere(function ($q) {
                $q->where('check_out_date', '<', now()->toDateString());
            });
    }
}
