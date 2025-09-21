<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationChannel;

class SMSChannel implements NotificationChannel
{
    public function send(string $message, array $data = []): array
    {
        // Simulate SMS sending
        $phone = $data['phone'] ?? '+1234567890';

        sleep(2); // Simulate processing time

        return [
            'success' => true,
            'channel' => 'sms',
            'message' => "SMS sent to {$phone}",
            'content' => substr($message, 0, 160), // SMS character limit
            'timestamp' => now()->toDateTimeString()
        ];
    }

    public function getChannelName(): string
    {
        return 'sms';
    }

    public function canSend(): bool
    {
        return config('notifications.channels.sms.enabled', false);
    }
}
