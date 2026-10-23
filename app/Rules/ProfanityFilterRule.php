<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProfanityFilterRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Load profanity wordlist
        $profanityList = require resource_path('data/profanity_wordlist.php');
        
        // Convert value to lowercase for case-insensitive checking
        $lowerValue = mb_strtolower($value, 'UTF-8');
        
        // Check for exact matches and partial matches
        foreach ($profanityList as $badWord) {
            $lowerBadWord = mb_strtolower($badWord, 'UTF-8');
            
            // Check if the bad word appears as a whole word or part of the text
            // Use word boundaries for better detection
            if (preg_match('/\b' . preg_quote($lowerBadWord, '/') . '\b/u', $lowerValue)) {
                $fail('The :attribute contains inappropriate language. Please use kid-friendly words.');
                return;
            }
            
            // Also check for variations without word boundaries (e.g., "bad" in "badword")
            if (str_contains($lowerValue, $lowerBadWord)) {
                $fail('The :attribute contains inappropriate language. Please use kid-friendly words.');
                return;
            }
        }
    }
}
