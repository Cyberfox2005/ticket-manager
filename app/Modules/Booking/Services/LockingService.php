<?php

namespace App\Modules\Booking\Services;

use Illuminate\Support\Facades\Cache;

class LockingService
{
    /**
     * High-Concurrency Seat Locking
     */
    public function lockSeat(int $eventId, int $seatId, int $userId, int $minutes = 10): bool
    {
        $lockKey = "lock:event:{$eventId}:seat:{$seatId}";

        // Atomic lock acquisition
        return Cache::lock($lockKey, $minutes * 60)->get();
    }

    public function releaseSeat(int $eventId, int $seatId): void
    {
        $lockKey = "lock:event:{$eventId}:seat:{$seatId}";
        Cache::lock($lockKey)->release();
    }

    public function isLocked(int $eventId, int $seatId): bool
    {
        $lockKey = "lock:event:{$eventId}:seat:{$seatId}";
        return Cache::has($lockKey);
    }
}
