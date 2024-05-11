<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SenderReceiverCountRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Count the number of senders and receivers in the addresses array
        $senders = collect($value)->where('type', 'sender')->count();
        $receivers = collect($value)->where('type', 'receiver')->count();

        // Validate that there is exactly one sender and one receiver
        if ($senders !== 1) {
            $fail('Address must contain only one sender');
        }

        if ($receivers !== 1) {
            $fail('Address must contain only one receiver');
        }
    }
}
