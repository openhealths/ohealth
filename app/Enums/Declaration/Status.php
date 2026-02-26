<?php

declare(strict_types=1);

namespace App\Enums\Declaration;

use App\Traits\EnumUtils;

enum Status: string
{
    use EnumUtils;

    case DRAFT = 'DRAFT';
    case NEW = 'NEW';
    case APPROVED = 'APPROVED';
    case SIGNED = 'SIGNED';
    case ACTIVE = 'active';
    case TERMINATED = 'terminated';
    case REJECTED = 'REJECTED';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('declarations.status.draft'),
            self::NEW => __('declarations.status.new'),
            self::APPROVED => __('declarations.status.approved'),
            self::SIGNED => __('declarations.status.signed'),
            self::ACTIVE => __('declarations.status.active'),
            self::REJECTED => __('declarations.status.rejected'),
            self::CANCELLED => __('declarations.status.cancelled'),
            self::TERMINATED => __('declarations.status.terminated')
        };
    }
}
