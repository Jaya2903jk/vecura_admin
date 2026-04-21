<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send($userId, $title, $message, $type, $refId = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'reference_id' => $refId,
            'is_read' => 0
        ]);
    }
}
