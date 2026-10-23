<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherNotification extends Model
{
    protected $fillable = [
        'teacher_id', 'student_id', 'type', 'title', 'message', 'data', 'is_read', 'read_at',
    ];
    
    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the teacher who receives this notification
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
    
    /**
     * Get the student related to this notification
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
    
    /**
     * Get icon for notification type
     */
    public function getIconAttribute(): string
    {
        return match($this->type) {
            'username_change' => 'bi-person-badge',
            'birthdate_change' => 'bi-calendar-event',
            'inappropriate_content' => 'bi-exclamation-triangle',
            'profile_update' => 'bi-pencil-square',
            default => 'bi-info-circle',
        };
    }
    
    /**
     * Get color class for notification type
     */
    public function getColorClassAttribute(): string
    {
        return match($this->type) {
            'username_change' => 'text-info',
            'birthdate_change' => 'text-warning',
            'inappropriate_content' => 'text-danger',
            'profile_update' => 'text-success',
            default => 'text-secondary',
        };
    }
}
