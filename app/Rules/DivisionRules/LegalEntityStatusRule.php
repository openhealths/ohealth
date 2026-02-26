<?php

declare(strict_types=1);

namespace App\Rules\DivisionRules;

use Closure;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\ValidationRule;

class LegalEntityStatusRule implements ValidationRule
{
    /**
     * Run the validation rule. Check that legal entity is in ‘ACTIVE’ or ‘SUSPENDED’ status
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $status = legalEntity()->status;

        // CustomValidationException
        if ($status !== 'ACTIVE' && $status !== 'SUSPENDED') {
            throw new CustomValidationException($this->message(), 'custom');
        }
    }

    protected function message(): string
    {
        return __('validation.attributes.healthcareService.error.legalEntity.status');
    }
}
