<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasSpecialCharacter implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/[!@#$%]/', $value) ) {
            $fail('The :attribute must have at least 1 special character (only use the following characters: ! @ # $ or %)');
        }
    }
}
