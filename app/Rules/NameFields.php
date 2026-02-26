<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * See: https://e-health-ua.atlassian.net/wiki/spaces/ESOZ/pages/19331907630/REST+API+Create+Update+Person+Request+v2+API-010-055-0015#Validate-name-fields
 */
class NameFields implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Checking for allowed symbols
        $allowed = "/^[А-ЩЬЮЯҐЄІЇа-щьюяґєії\s.\-\/']+$/u";
        if (!preg_match($allowed, $value)) {
            $fail("Поле може містити тільки літери української абетки, пробіли, крапки, дефіси, слеші та апострофи.");

            return;
        }

        // Checking first symbol
        $start = "/^[А-ЩЬЮЯҐЄІЇа-щьюяґєії]/u";
        if (!preg_match($start, $value)) {
            $fail("Поле повинно починатися з літери української абетки.");

            return;
        }

        // Checking last symbol
        $last = "/[\s\-\/']$/u";
        if (preg_match($last, $value)) {
            $fail("Поле не може закінчуватися пробілом, дефісом, слешем або апострофом.");

            return;
        }

        // Checking repeated special characters
        $double = "/([\s.\-\/'])\\1/u";
        if (preg_match($double, $value)) {
            $fail("Поле не може містити однакові спеціальні символи поспіль (пробіл, крапка, дефіс, слеш, апостроф).");
        }
    }
}
