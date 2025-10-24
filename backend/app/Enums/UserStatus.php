<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';
    case REJECTED = 'rejected';

    /**
     * Get the display name for the status
     */
    public function label(): string
    {
        return match($this) {
            self::ACTIVE => '활성',
            self::PENDING => '승인 대기',
            self::SUSPENDED => '정지',
            self::REJECTED => '거부됨',
        };
    }

    /**
     * Get the badge color for the status
     */
    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'green',
            self::PENDING => 'yellow',
            self::SUSPENDED => 'red',
            self::REJECTED => 'gray',
        };
    }

    /**
     * Get all status values as array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Check if status is active
     */
    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    /**
     * Check if status is pending
     */
    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}
