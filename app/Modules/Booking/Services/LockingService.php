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
        // Attempt to get the lock for 0 seconds; if it fails the seat is already locked.
        $lock = Cache::lock($lockKey, 600);
        if ($lock->get()) {
            // We got it — immediately release and report not locked
            $lock->release();
            return false;
        }
        return true;
    }
}
