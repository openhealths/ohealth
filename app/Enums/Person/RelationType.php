<?php

declare(strict_types=1);

namespace App\Enums\Person;

use App\Traits\EnumUtils;

enum RelationType: string
{
    use EnumUtils;

    case PRIMARY = 'PRIMARY';
    case SECONDARY = 'SECONDARY';

    public function label(): string
    {
        return match ($this) {
            self::PRIMARY => __('patients.relation_type.primary'),
            self::SECONDARY => __('patients.relation_type.secondary')
        };
    }
}
