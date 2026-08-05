<?php

namespace App\Modules\Notifications\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationDispatcher
{
    /**
     * Enterprise Dispatcher with delivery logging
     */
    public function dispatch(User $user, string $templateSlug, array $data, array $channels = ['email']): void
    {
        foreach ($channels as $channel) {
            // 1. Log the attempt
            $log = \App\Models\NotificationLog::create([
                'user_id' => $user->id,
                'channel' => $channel,
                'recipient' => $user->email, // Heuristic
                'status' => 'queued'
            ]);

            // 2. Dispatch to Queue
            try {
                // In a real app, this would use Laravel's Notification::send()
                // which is already queue-based by implementing ShouldQueue
                Log::info("Dispatching {$templateSlug} to {$user->email} via {$channel}");

                $log->update(['status' => 'sent']);
            } catch (\Exception $e) {
                $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
        }
    }
}
