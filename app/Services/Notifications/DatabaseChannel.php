<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationChannel;
use App\Models\NotificationLog;

class DatabaseChannel implements NotificationChannel
{
    public function send(string $message, array $data = []): array
    {
        // Store notification in database
        $notification = NotificationLog::create([
            'channel' => 'database',
            'message' => $message,
            'data' => $data,
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return [
            'success' => true,
            'channel' => 'database',
            'message' => "Notification stored in database",
            'log_id' => $notification->id,
            'timestamp' => now()->toDateTimeString()
        ];
    }

    public function getChannelName(): string
    {
        return 'database';
    }

    public function canSend(): bool
    {
        return config('notifications.channels.database.enabled', true);
    }
}
