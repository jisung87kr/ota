<?php

namespace App\Enums;

enum Role: string
{
    case CUSTOMER = 'customer';
    case ACCOMMODATION_MANAGER = 'accommodation_manager';
    case ADMIN = 'admin';

    /**
     * Get all role values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get role label for display
     */
    public function label(): string
    {
        return match($this) {
            self::CUSTOMER => '고객',
            self::ACCOMMODATION_MANAGER => '숙박상품 관리자',
            self::ADMIN => '관리자',
        };
    }

    /**
     * Check if role is customer
     */
    public function isCustomer(): bool
    {
        return $this === self::CUSTOMER;
    }

    /**
     * Check if role is accommodation manager
     */
    public function isAccommodationManager(): bool
    {
        return $this === self::ACCOMMODATION_MANAGER;
    }

    /**
     * Check if role is admin
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }
}
