<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

readonly class OnlyOnePrimaryDiagnosis implements ValidationRule
{
    public function __construct(
        private ?string $classCode = null,
        private array $conditions = []
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $diagnoses = collect($value);

        // An intervention may carry no diagnosis at all; the ones it does carry are checked like any other
        if ($diagnoses->isEmpty()) {
            return;
        }

        $primaryCount = $diagnoses->filter(fn (array $diagnosis) => $diagnosis['roleCode'] === 'primary')->count();

        if ($primaryCount !== 1) {
            $fail(__('conditions.validation.single_primary_diagnosis'));

            return;
        }

        if (empty($this->classCode)) {
            return;
        }

        $expectedSystem = $this->classCode === 'PHC'
            ? 'eHealth/ICPC2/condition_codes'
            : 'eHealth/ICD10_AM/condition_codes';

        $primaryIndex = $diagnoses->search(fn (array $diagnosis) => ($diagnosis['roleCode'] ?? '') === 'primary');

        $condition = $this->conditions[$primaryIndex] ?? null;
        if (empty($condition)) {
            return;
        }

        if (($condition['codeSystem'] ?? '') !== $expectedSystem) {
            $fail(__('conditions.validation.primary_diagnosis_code_system', ['system' => $expectedSystem]));
        }
    }
}
