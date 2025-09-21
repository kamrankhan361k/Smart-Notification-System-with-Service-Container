<?php

namespace App\Contracts\Notifications;

interface NotificationChannel
{
    public function send(string $message, array $data = []): array;
    public function getChannelName(): string;
    public function canSend(): bool;
}
