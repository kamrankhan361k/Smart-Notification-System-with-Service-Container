<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationChannel;

class EmailChannel implements NotificationChannel
{
    public function send(string $message, array $data = []): array
    {
        // Simulate email sending
        $email = $data['email'] ?? 'user@example.com';
        $subject = $data['subject'] ?? 'Notification';

        sleep(1); // Simulate processing time

        return [
            'success' => true,
            'channel' => 'email',
            'message' => "Email sent to {$email}",
            'subject' => $subject,
            'content' => $message,
            'timestamp' => now()->toDateTimeString()
        ];
    }

    public function getChannelName(): string
    {
        return 'email';
    }

    public function canSend(): bool
    {
        return config('notifications.channels.email.enabled', true);
    }
}
