<?php

namespace App\Modules\Booking\Services;

use App\Models\Reservation;
use App\Models\Seat;
use App\Modules\Booking\Services\LockingService;
use App\Modules\Pricing\Services\PricingCalculator;
use App\Modules\Pricing\Services\Adjusters\EarlyBirdAdjuster;

class BookingService
{
    public function __construct(
        protected LockingService $lockingService,
        protected PricingCalculator $pricingCalculator
    ) {
        // Register default adjusters
        $this->pricingCalculator->addAdjuster(new EarlyBirdAdjuster());
    }

    /**
     * Get a detailed price quote for a seat
     */
    public function getQuote(int $eventId, int $seatId, ?User $user)
    {
        $ticketType = \App\Models\TicketType::where('event_id', $eventId)->first(); // Simple heuristic for now
        return $this->pricingCalculator->calculate($ticketType, $user);
    }

    /**
     * World-Class Booking Engine: Atomic Seat Selection
     */
    public function selectSeat(int $eventId, int $seatId, int $userId)
    {
        return DB::transaction(function () use ($eventId, $seatId, $userId) {
            // 1. Check Redis Lock (Concurrency Protection)
            if ($this->lockingService->isLocked($eventId, $seatId)) {
                throw new \Exception('Seat is currently being booked by another user', 422);
            }

            // 2. Check Database Status (Pessimistic Locking)
            $seat = Seat::where('id', $seatId)->lockForUpdate()->first();

            // Check if seat is already sold in another order
            $isSold = DB::table('tickets')
                ->join('orders', 'tickets.order_id', '=', 'orders.id')
                ->where('orders.event_id', $eventId)
                ->where('tickets.seat_id', $seatId)
                ->where('orders.status', 'paid')
                ->exists();

            if ($isSold) {
                throw new \Exception('Seat is already sold', 422);
            }

            // 3. Acquire Redis Lock
            if (!$this->lockingService->lockSeat($eventId, $seatId, $userId)) {
                throw new \Exception('Failed to secure seat lock', 422);
            }

            // 4. Create DB Reservation (Timer Record)
            $reservation = Reservation::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'seat_id' => $seatId,
                'expires_at' => Carbon::now()->addMinutes(10),
                'status' => 'pending'
            ]);

            // Dispatch Event
            \App\Modules\Shared\Events\SeatLocked::dispatch($reservation);

            return $reservation;
        });
    }

    public function cancelReservation(int $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $this->lockingService->releaseSeat($reservation->event_id, $reservation->seat_id);
        $reservation->update(['status' => 'expired']);
    }
}
