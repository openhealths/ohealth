<?php

declare(strict_types=1);

namespace App\Enums\Person;

use App\Traits\EnumUtils;

enum Status: string
{
    use EnumUtils;

    case DRAFT = 'DRAFT';
    case NEW = 'NEW';
    case APPROVED = 'APPROVED';
    case SIGNED = 'SIGNED';
    case REJECTED = 'REJECTED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('patients.status.draft'),
            self::NEW => __('patients.status.new'),
            self::APPROVED => __('patients.status.approved'),
            self::SIGNED => __('patients.status.signed'),
            self::REJECTED => __('patients.status.rejected'),
            self::CANCELLED => __('patients.status.cancelled')
        };
    }

    /**
     * Gets the color class for UI badges.
     */
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'badge-purple',
            self::NEW, self::APPROVED, self::SIGNED => 'badge-green',
            self::REJECTED, self::CANCELLED => 'badge-red'
        };
    }
}
