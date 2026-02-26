<?php

declare(strict_types=1);

namespace App\Enums\Employee;

use App\Traits\EnumUtils;

enum RequestStatus: string
{
    use EnumUtils;

    case NEW = 'NEW';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case SIGNED = 'SIGNED';
    case EXPIRED = 'EXPIRED';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Новий',
            self::APPROVED => 'Підтверджено',
            self::REJECTED => 'Відхилено',
            self::SIGNED => 'Підписано в ЕСОЗ',
            self::EXPIRED => 'Протермінований',
        };
    }

    /**
     * Returns an array of statuses pending
     * final synchronization upon user login.
     */
    public static function getStatusesForSync(): array
    {
        return [
            self::NEW->value,
            self::SIGNED->value,
        ];
    }
}
