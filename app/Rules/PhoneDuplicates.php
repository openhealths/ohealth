<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class PhoneDuplicates implements ValidationRule
{
    /**
     * This property will hold the array of phones if it's passed via the constructor.
     * It is nullable to support both old and new call methods.
     *
     * @var array|null
     */
    protected ?array $phonesFromConstructor;

    /**
     * The constructor can optionally accept an array of phones for backward compatibility.
     */
    public function __construct(?array $phones = null)
    {
        $this->phonesFromConstructor = $phones;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phonesToValidate = $this->phonesFromConstructor ?? $value;

        if (!is_array($phonesToValidate)) {
            return;
        }

        // Check if the $phonesToValidate is array of phones or single phone
        $isMultiPhone = isset($phonesToValidate[0]) && is_array($phonesToValidate[0]);

        if (!$isMultiPhone) {
            // For single phone further checking not necessary
            return;
        }

        $types = Arr::pluck($phonesToValidate, 'type');
        $typeCounts = array_count_values($types);

        foreach ($typeCounts as $type => $count) {
            if ($count > 1) {
                $fail(__('validation.phone.duplicates'));

                return;
            }
        }
    }
}
