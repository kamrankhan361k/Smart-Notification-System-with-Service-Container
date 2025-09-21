<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Notifications\NotificationManager;

class NotificationController extends Controller
{
    protected $notificationManager;

    public function __construct(NotificationManager $notificationManager)
    {
        $this->notificationManager = $notificationManager;
    }

    public function sendNotification(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'channels' => 'array',
            'channels.*' => 'in:email,sms,slack,database',
            'email' => 'sometimes|email',
            'phone' => 'sometimes|string',
            'channel' => 'sometimes|string',
        ]);

        $message = $request->input('message');
        $channels = $request->input('channels', []);
        $data = $request->only(['email', 'phone', 'channel']);

        $results = $this->notificationManager->send($message, $channels, $data);

        return response()->json([
            'success' => true,
            'message' => 'Notification processed',
            'results' => $results,
            'sent_via' => $channels ?: 'default channels'
        ]);
    }

    public function getChannels()
    {
        $availableChannels = $this->notificationManager->getAvailableChannels();
        $config = config('notifications');

        return response()->json([
            'available_channels' => $availableChannels,
            'configuration' => $config,
            'active_channels' => array_filter($config['channels'], fn($channel) => $channel['enabled'])
        ]);
    }

    public function testChannels()
    {
        $channels = ['email', 'slack', 'database'];
        $results = [];

        foreach ($channels as $channel) {
            $results[$channel] = $this->notificationManager->send(
                "Test message for {$channel} channel",
                [$channel],
                ['email' => 'test@example.com', 'phone' => '+1234567890']
            );
        }

        return response()->json([
            'message' => 'Test notifications sent',
            'results' => $results
        ]);
    }

    public function showDemo()
    {
        return view('notifications-demo');
    }
}
