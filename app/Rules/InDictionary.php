<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Translation\PotentiallyTranslatedString;

class InDictionary implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @param  string|array  $dictionaryNames  One or multiple dictionary names to check against
     */
    public function __construct(protected string|array $dictionaryNames)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute  The name of the attribute being validated
     * @param  mixed  $value  The value of the attribute being validated
     * @param  Closure(string): PotentiallyTranslatedString  $fail  The callback to invoke if validation fails
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Normalize dictionary names to array for unified processing
        $names = is_array($this->dictionaryNames)
            ? $this->dictionaryNames
            : [$this->dictionaryNames];

        // A flag to determine if the value exists in at least one dictionary
        $isValid = false;

        foreach ($names as $name) {
            if ($name === 'eHealth/ICF/classifiers') {
                $dictionaryKeys = array_keys(dictionary()
                    ->getLargeDictionary('eHealth/ICF/classifiers', false)
                    ->getFlattenedChildValues());
            } elseif ($name === 'eHealth/ICD10_AM/condition_codes') {
                $dictionaryKeys = DB::table('icd_10')
                    ->select(['code'])
                    ->pluck('code')
                    ->toArray();
            } elseif ($name === 'device_definition_classification_type') {
                // Convert all keys to string
                $dictionaryKeys = dictionary()->getDictionary('device_definition_classification_type', false)
                    ->keys()
                    ->map(static fn (int|string $key) => (string)$key)
                    ->toArray();
            } else {
                $dictionaryKeys = array_keys(dictionary()->getDictionary($name));
            }

            if (in_array($value, $dictionaryKeys, true)) {
                $isValid = true;
                break;
            }
        }

        // Fail validation if value not found in any dictionary
        if (!$isValid) {
            $fail(__('Недопустиме значення :attribute'));
        }
    }
}
