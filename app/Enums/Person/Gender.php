<?php

declare(strict_types=1);

namespace App\Enums\Person;

use App\Traits\EnumUtils;

enum Gender: string
{
    use EnumUtils;

    case MALE = 'MALE';
    case FEMALE = 'FEMALE';

    public function label(): string
    {
        return match ($this) {
            self::MALE => __('чоловіча'),
            self::FEMALE => __('жіноча')
        };
    }
}
