<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\Notifications\NotificationManager;
use App\Contracts\Notifications\NotificationChannel;
use App\Services\Notifications\NotificationManagerService;
use App\Services\Notifications\EmailChannel;
use App\Services\Notifications\SMSChannel;
use App\Services\Notifications\SlackChannel;
use App\Services\Notifications\DatabaseChannel;

class NotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the NotificationManager interface to our implementation
        $this->app->singleton(NotificationManager::class, function ($app) {
            return new NotificationManagerService();
        });

        // Bind individual channels
        $this->app->bind(EmailChannel::class);
        $this->app->bind(SMSChannel::class);
        $this->app->bind(SlackChannel::class);
        $this->app->bind(DatabaseChannel::class);

        // Register channel mappings
        $this->app->bind('notification.channel.email', EmailChannel::class);
        $this->app->bind('notification.channel.sms', SMSChannel::class);
        $this->app->bind('notification.channel.slack', SlackChannel::class);
        $this->app->bind('notification.channel.database', DatabaseChannel::class);
    }

    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__.'/../../config/notifications.php' => config_path('notifications.php'),
        ], 'notifications-config');
    }
}
