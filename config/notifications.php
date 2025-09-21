<?php

return [
    'default_channels' => ['email', 'database'],

    'channels' => [
        'email' => [
            'enabled' => true,
            'priority' => 'high',
        ],
        'sms' => [
            'enabled' => env('SMS_NOTIFICATIONS_ENABLED', false),
            'priority' => 'urgent',
        ],
        'slack' => [
            'enabled' => true,
            'priority' => 'medium',
        ],
        'database' => [
            'enabled' => true,
            'priority' => 'low',
        ],
    ],

    'channel_map' => [
        'email' => \App\Services\Notifications\EmailChannel::class,
        'sms' => \App\Services\Notifications\SMSChannel::class,
        'slack' => \App\Services\Notifications\SlackChannel::class,
        'database' => \App\Services\Notifications\DatabaseChannel::class,
    ],

    'max_retries' => 3,
    'timeout' => 30,
];
