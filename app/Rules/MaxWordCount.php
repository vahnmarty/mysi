<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MaxWordCount implements ValidationRule
{
    public $max = 75;

    public function __construct($max)
    {
        $this->max = $max;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ( str_word_count($value) > $this->max ) {
            $fail("You have reached the maximum word count. Please limit answer to {$this->max} words.");
        }
    }
}
