<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'link',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->read_at = now();
            $this->save();
        }
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): void
    {
        $this->read_at = null;
        $this->save();
    }

    /**
     * Check if notification is read
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Get notification color based on type
     */
    public function getColor(): string
    {
        return match($this->type) {
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'error' => '#ef4444',
            'achievement' => '#a78bfa',
            'info' => '#3b82f6',
            default => '#6366f1',
        };
    }

    /**
     * Get default icon if none specified
     */
    public function getIcon(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        return match($this->type) {
            'success' => '✅',
            'warning' => '⚠️',
            'error' => '❌',
            'achievement' => '🏆',
            'info' => 'ℹ️',
            default => '🔔',
        };
    }

    /**
     * Get time ago string
     */
    public function getTimeAgo(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope to get unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope to get read notifications
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }
}
