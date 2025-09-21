<?php

namespace App\Contracts\Notifications;

interface NotificationManager
{
    public function send(string $message, array $channels = [], array $data = []): array;
    public function addChannel(NotificationChannel $channel): void;
    public function getAvailableChannels(): array;
    public function setDefaultChannels(array $channels): void;
}
