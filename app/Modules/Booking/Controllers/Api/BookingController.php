<?php

namespace App\Modules\Booking\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Booking\Services\BookingService;
use App\Modules\Shared\Infrastructure\ApiResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use ApiResponse;

    public function __construct(protected BookingService $bookingService) {}

    public function lockSeat(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_id' => 'required|exists:seats,id',
        ]);

        try {
            $reservation = $this->bookingService->selectSeat(
                $validated['event_id'],
                $validated['seat_id'],
                $request->user()->id
            );

            return $this->successResponse($reservation, 'Seat locked for 10 minutes');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 422);
        }
    }

    public function cancel(int $id)
    {
        try {
            $this->bookingService->cancelReservation($id);
            return $this->successResponse(null, 'Reservation cancelled and seat released');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
