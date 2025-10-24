<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';           // 결제 대기
    case CONFIRMED = 'confirmed';       // 예약 확정
    case CHECKED_IN = 'checked_in';     // 체크인 완료
    case CHECKED_OUT = 'checked_out';   // 체크아웃 완료
    case CANCELLED = 'cancelled';       // 취소됨

    /**
     * Get all status values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get status label for display
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING => '결제 대기',
            self::CONFIRMED => '예약 확정',
            self::CHECKED_IN => '체크인 완료',
            self::CHECKED_OUT => '체크아웃 완료',
            self::CANCELLED => '취소됨',
        };
    }

    /**
     * Get status color for UI
     */
    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::CONFIRMED => 'green',
            self::CHECKED_IN => 'blue',
            self::CHECKED_OUT => 'gray',
            self::CANCELLED => 'red',
        };
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this, [self::PENDING, self::CONFIRMED]);
    }

    /**
     * Check if booking can be checked in
     */
    public function canBeCheckedIn(): bool
    {
        return $this === self::CONFIRMED;
    }

    /**
     * Check if booking can be checked out
     */
    public function canBeCheckedOut(): bool
    {
        return $this === self::CHECKED_IN;
    }
}
