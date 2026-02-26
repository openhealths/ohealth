<?php

declare(strict_types=1);

namespace App\Rules\DivisionRules;

use Closure;
use App\Models\LegalEntity;
use App\Exceptions\CustomValidationException;
use Illuminate\Contracts\Validation\ValidationRule;

class LocationTypeRule implements ValidationRule
{
    public function __construct(protected array $division)
    {
    }

    /**
     * Run the validation rule. Check that location exists in request for legal entity with type PHARMACY
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $localEntityType = legalEntity()->type->name;
        $hasLocation = $this->division['location']['longitude'] && $this->division['location']['latitude'];

        // CustomValidationException
        if ($localEntityType === LegalEntity::TYPE_PHARMACY && !$hasLocation) {
            throw new CustomValidationException($this->message(), 'custom');
        }
    }

    protected function message(): string
    {
        return __('divisions.errors.location.required_type');
    }
}
