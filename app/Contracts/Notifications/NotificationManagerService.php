<?php

namespace App\Services\Notifications;

use App\Contracts\Notifications\NotificationManager;
use App\Contracts\Notifications\NotificationChannel;
use Illuminate\Support\Facades\Log;

class NotificationManagerService implements NotificationManager
{
    protected $channels = [];
    protected $defaultChannels = ['email', 'database'];

    public function __construct()
    {
        $this->initializeDefaultChannels();
    }

    protected function initializeDefaultChannels(): void
    {
        foreach ($this->defaultChannels as $channel) {
            $this->addChannel($this->resolveChannel($channel));
        }
    }

    public function send(string $message, array $channels = [], array $data = []): array
    {
        $channelsToUse = empty($channels) ? $this->defaultChannels : $channels;
        $results = [];

        foreach ($channelsToUse as $channelName) {
            $channel = $this->getChannel($channelName);

            if ($channel && $channel->canSend()) {
                try {
                    $results[] = $channel->send($message, $data);
                } catch (\Exception $e) {
                    $results[] = [
                        'success' => false,
                        'channel' => $channelName,
                        'error' => $e->getMessage(),
                        'timestamp' => now()->toDateTimeString()
                    ];
                    Log::error("Notification failed for channel {$channelName}: " . $e->getMessage());
                }
            }
        }

        return $results;
    }

    public function addChannel(NotificationChannel $channel): void
    {
        $this->channels[$channel->getChannelName()] = $channel;
    }

    public function getAvailableChannels(): array
    {
        return array_keys($this->channels);
    }

    public function setDefaultChannels(array $channels): void
    {
        $this->defaultChannels = $channels;
    }

    protected function getChannel(string $channelName): ?NotificationChannel
    {
        return $this->channels[$channelName] ?? null;
    }

    protected function resolveChannel(string $channelName): NotificationChannel
    {
        $channelClass = config("notifications.channel_map.{$channelName}");

        if ($channelClass && class_exists($channelClass)) {
            return app($channelClass);
        }

        throw new \InvalidArgumentException("Channel {$channelName} is not configured properly");
    }
}
