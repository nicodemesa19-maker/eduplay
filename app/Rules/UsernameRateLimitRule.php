<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UsernameRateLimitRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = Auth::user();
        
        if (!$user) {
            $fail('You must be logged in to change your username.');
            return;
        }
        
        // Check if user has exceeded the daily limit (3 changes per day)
        $today = Carbon::today();
        
        // Reset counter if last change was before today
        if ($user->last_username_change && Carbon::parse($user->last_username_change)->isBefore($today)) {
            // Counter will be reset in the controller after successful update
            return;
        }
        
        // Check if user has reached the limit
        if ($user->username_changes_count >= 3 && 
            $user->last_username_change && 
            Carbon::parse($user->last_username_change)->isSameDay($today)) {
            
            $nextResetTime = Carbon::parse($user->last_username_change)->addDay()->format('M j, Y');
            $fail("You can only change your username 3 times per day. Try again on {$nextResetTime}.");
            return;
        }
    }
}
