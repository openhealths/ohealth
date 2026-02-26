<?php

declare(strict_types=1);

namespace App\Enums\Contract;

use App\Traits\EnumUtils;

enum Type: string
{
    use EnumUtils;

    case DRAFT = 'DRAFT';
    case CONTRACT_REQUESTS = 'CONTRACT_REQUESTS';
    case CONTRACTS = 'CONTRACTS';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => __('forms.status.drafts'),
            self::CONTRACT_REQUESTS => __('contracts.status.requests'),
            self::CONTRACTS => __('forms.contracts')
        };
    }
}
