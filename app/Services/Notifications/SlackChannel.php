<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationChannel;

class SlackChannel implements NotificationChannel
{
    public function send(string $message, array $data = []): array
    {
        // Simulate Slack message
        $channel = $data['channel'] ?? '#general';

        return [
            'success' => true,
            'channel' => 'slack',
            'message' => "Slack message sent to {$channel}",
            'content' => $message,
            'timestamp' => now()->toDateTimeString()
        ];
    }

    public function getChannelName(): string
    {
        return 'slack';
    }

    public function canSend(): bool
    {
        return config('notifications.channels.slack.enabled', true);
    }
}
