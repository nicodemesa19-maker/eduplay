<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileChangeLog extends Model
{
    protected $fillable = [
        'user_id', 'field_name', 'old_value', 'new_value',
        'changed_by_role', 'changed_by_user_id', 'ip_address', 'user_agent',
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the user whose profile was changed
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get the user who made the change (teacher or student)
     */
    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
    
    /**
     * Get formatted date
     */
    public function getFormattedDate(): string
    {
        return $this->created_at->diffForHumans();
    }
    
    /**
     * Get formatted old value for display
     */
    public function getFormattedOldValue(): string
    {
        return $this->formatValue($this->old_value);
    }
    
    /**
     * Get formatted new value for display
     */
    public function getFormattedNewValue(): string
    {
        return $this->formatValue($this->new_value);
    }
    
    /**
     * Format value for display
     */
    private function formatValue(?string $value): string
    {
        if ($value === null) {
            return '(empty)';
        }
        
        if ($this->field_name === 'password') {
            return '[hidden]';
        }
        
        if ($this->field_name === 'hobbies') {
            $hobbies = json_decode($value, true);
            return is_array($hobbies) ? implode(', ', $hobbies) : $value;
        }
        
        if (in_array($this->field_name, ['practice_reminders', 'streak_alerts'])) {
            return $value ? 'On' : 'Off';
        }
        
        return (string) $value;
    }
}