<?php

declare(strict_types=1);

namespace App\Enums\Person;

use App\Traits\EnumUtils;

/**
 * @see: https://e-health-ua.atlassian.net/wiki/spaces/EH/pages/19622887426/RC_Confidant+person+relationship+request+status+model
 */
enum ConfidantPersonRelationshipRequestStatus: string
{
    use EnumUtils;

    case NEW = 'NEW';
    case APPROVED = 'APPROVED';
    case COMPLETED = 'COMPLETED';
    case CANCELLED = 'CANCELLED';
    case EXPIRED = 'EXPIRED';

    public function label(): string
    {
        return match ($this) {
            self::NEW => __('patients.status.new'),
            self::APPROVED => __('patients.status.approved'),
            self::COMPLETED => __('patients.status.completed'),
            self::CANCELLED => __('patients.status.cancelled'),
            self::EXPIRED => __('patients.status.expired')
        };
    }
}
