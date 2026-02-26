<?php

declare(strict_types=1);

namespace App\Enums\Equipment;

use App\Traits\EnumUtils;

/**
 * https://e-health-ua.atlassian.net/wiki/spaces/ESOZ/pages/18426758441/DRAFT+device_name_type
 */
enum Type: string
{
    use EnumUtils;

    case PATIENT_REPORTED = 'patient_reported';
    case REGISTERED = 'registered';
    case USER_FRIENDLY = 'user_friendly';

    public function label(): string
    {
        return match($this) {
            self::PATIENT_REPORTED => __('equipments.type.patient_reported'),
            self::REGISTERED => __('equipments.type.registered'),
            self::USER_FRIENDLY => __('equipments.type.user_friendly')
        };
    }

    /**
     * Allowed names for creating equipment.
     *
     * @return array
     */
    public static function allowedForEquipment(): array
    {
        return [
            self::REGISTERED->value => __('equipments.type.registered'),
            self::USER_FRIENDLY->value => __('equipments.type.user_friendly')
        ];
    }
}
