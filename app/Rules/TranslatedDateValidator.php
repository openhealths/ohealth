<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Validation\Validator;
use Override;

/**
 * Custom validator that automatically translates special date keywords in validation messages.
 */
class TranslatedDateValidator extends Validator
{
    #[Override]
    public function getDisplayableValue($attribute, $value): string
    {
        $specialDates = ['today', 'tomorrow', 'yesterday', 'now'];

        if (in_array($value, $specialDates, true)) {
            return __("validation.values.$value") ?: $value;
        }

        return parent::getDisplayableValue($attribute, $value);
    }
}
