<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxWordCount implements ValidationRule
{
    public $max = 75;
    public $cap;

    public function __construct($max, $cap = null)
    {
        $this->max = $max;
        $this->cap = $cap ?? $max + 25;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( str_word_count($value) > $this->cap ) {
            $fail("You have exceeded the number of words allowed for this field.  Please limit your answer to around {$this->max} words.");
        }
    }
}
