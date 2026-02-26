<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PhoneNumber implements ValidationRule
{
    public function __construct(protected int $minDigits = 9)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^\+380[0-9]{' . $this->minDigits . '}$/', $value)) {
            $fail(__('validation.phone', ['min' => $this->minDigits]));
        }
    }
}
