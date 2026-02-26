<?php

declare(strict_types=1);

namespace App\Enums\Division;

use App\Traits\EnumUtils;

enum Type: string
{
    use EnumUtils;

    case TYPE_FAP = 'FAP';
    case TYPE_CLINIC = 'CLINIC';
    case TYPE_AMBULANT_CLINIC = 'AMBULANT_CLINIC';
    case TYPE_LICENSED_UNIT = 'LICENSED_UNIT';
    case TYPE_DRUGSTORE = 'DRUGSTORE';
    case TYPE_DRUGSTORE_POINT = 'DRUGSTORE_POINT';
}
