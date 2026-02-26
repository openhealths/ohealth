<?php

declare(strict_types=1);

namespace App\Enums\Employee;

use App\Traits\EnumUtils;

enum RevisionStatus: string
{
    use EnumUtils;

    case PENDING = 'PENDING';
    case APPLIED = 'APPLIED';
    case OUTDATED = 'OUTDATED';
    case SENT = 'SENT';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Очікує',
            self::APPLIED => 'Застосовано',
            self::OUTDATED => 'Застаріла',
            self::SENT => 'Відправлено в ЕСОЗ',
        };
    }
}
