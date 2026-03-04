<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phones = is_array($value) ? $value : [$value];

        foreach ($phones as $phone) {
            if (!preg_match('/^(011|010|012|015)\d{8}$/', $phone)) {
                $fail('The :attribute must be a valid Egyptian phone number starting with 011, 010, 012, or 015 and exactly 11 digits.');
            }
        }
    }
}
