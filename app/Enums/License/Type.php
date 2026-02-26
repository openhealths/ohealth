<?php

declare(strict_types=1);

namespace App\Enums\License;

use App\Traits\EnumUtils;

enum Type: string
{
    use EnumUtils;

    case MSP = 'MSP';
    case PHARMACY = 'PHARMACY';
    case PHARMACY_DRUGS = 'PHARMACY_DRUGS';

    public function label(): string
    {
        return match ($this) {
            self::MSP => __('licenses.type.msp'),
            self::PHARMACY => __('licenses.type.pharmacy'),
            self::PHARMACY_DRUGS => __('licenses.type.pharmacy_drugs')
        };
    }
}
