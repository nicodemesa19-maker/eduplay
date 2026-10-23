<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

class NotificationHelper
{
    /**
     * Send a notification to a user
     */
    public static function send(
        User $user,
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $link = null
    ): Notification {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'link' => $link,
        ]);
    }

    /**
     * Send a success notification
     */
    public static function success(User $user, string $title, string $message, ?string $link = null): Notification
    {
        return self::send($user, $title, $message, 'success', '✅', $link);
    }

    /**
     * Send an achievement notification
     */
    public static function achievement(User $user, string $title, string $message, ?string $link = null): Notification
    {
        return self::send($user, $title, $message, 'achievement', '🏆', $link);
    }

    /**
     * Send a warning notification
     */
    public static function warning(User $user, string $title, string $message, ?string $link = null): Notification
    {
        return self::send($user, $title, $message, 'warning', '⚠️', $link);
    }

    /**
     * Send an info notification
     */
    public static function info(User $user, string $title, string $message, ?string $link = null): Notification
    {
        return self::send($user, $title, $message, 'info', 'ℹ️', $link);
    }

    /**
     * Send notification to all users with a specific role
     */
    public static function sendToRole(
        string $role,
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $link = null
    ): int {
        $users = User::where('role', $role)->get();
        $count = 0;

        foreach ($users as $user) {
            self::send($user, $title, $message, $type, $icon, $link);
            $count++;
        }

        return $count;
    }

    /**
     * Send notification to multiple users
     */
    public static function sendToMany(
        array $userIds,
        string $title,
        string $message,
        string $type = 'info',
        ?string $icon = null,
        ?string $link = null
    ): int {
        $count = 0;

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                self::send($user, $title, $message, $type, $icon, $link);
                $count++;
            }
        }

        return $count;
    }
}
