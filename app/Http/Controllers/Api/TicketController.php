<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TicketController extends Controller
{
    public function myTickets(Request $request)
    {
        $tickets = $request->user()->tickets()
            ->with(['ticketType', 'order.event.venue'])
            ->latest()
            ->get();

        return response()->json($tickets);
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'qr_hash' => 'required|string',
        ]);

        $ticket = Ticket::where('qr_hash', $request->qr_hash)
            ->with(['user', 'ticketType', 'order.event'])
            ->first();

        if (! $ticket) {
            return response()->json(['valid' => false, 'message' => 'Invalid Ticket'], 404);
        }

        if ($ticket->validated_at) {
            return response()->json([
                'valid' => false,
                'message' => 'Ticket already used at ' . $ticket->validated_at->toDateTimeString(),
                'ticket' => $ticket
            ], 422);
        }

        // Check if event is happening now (allow some buffer)
        $event = $ticket->order->event;
        if (Carbon::now()->isBefore($event->start_at->subHours(2))) {
            return response()->json([
                'valid' => false,
                'message' => 'Event has not started yet',
                'ticket' => $ticket
            ], 422);
        }

        $ticket->update([
            'validated_at' => Carbon::now(),
            'validated_by' => $request->user()->id,
        ]);

        return response()->json([
            'valid' => true,
            'message' => 'Ticket validated successfully',
            'ticket' => $ticket
        ]);
    }
}
