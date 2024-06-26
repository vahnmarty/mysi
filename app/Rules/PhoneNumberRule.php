<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match("/^\d{10}$/", $value)) {
            if(substr($value, 0, 1) === '1'){
                $fail('Phone numbers cannot begin with 1.');
            }else{
                $fail('The phone number is not valid.  Enter a valid phone number.');
            }
        } 
    }
}
