<?php

namespace App\Modules\Booking\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class QueueManager
{
    protected string $prefix = 'waiting_room:';

    /**
     * Join the waiting room for a specific event
     */
    public function join(int $eventId, int $userId): string
    {
        $queueKey = $this->prefix . $eventId;
        $token = Str::random(32);

        // Use timestamp as score for FIFO
        Redis::zadd($queueKey, time(), $userId . ':' . $token);

        return $token;
    }

    /**
     * Check if user is allowed to proceed to checkout
     */
    public function canProceed(int $eventId, int $userId, string $token): bool
    {
        $queueKey = $this->prefix . $eventId;
        $maxAllowed = 100; // Only allow top 100 users at a time

        $rank = Redis::zrank($queueKey, $userId . ':' . $token);

        return $rank !== null && $rank < $maxAllowed;
    }

    public function leave(int $eventId, int $userId, string $token): void
    {
        $queueKey = $this->prefix . $eventId;
        Redis::zrem($queueKey, $userId . ':' . $token);
    }
}
