<?php

namespace App\Modules\Tickets\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Tickets\Services\TicketService;
use App\Modules\Shared\Infrastructure\ApiResponse;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    use ApiResponse;

    public function __construct(protected TicketService $ticketService) {}

    public function myTickets(Request $request)
    {
        $tickets = $this->ticketService->getUserTickets($request->user());
        return $this->successResponse($tickets);
    }

    public function validateTicket(Request $request)
    {
        $request->validate([
            'qr_hash' => 'required|string',
        ]);

        try {
            $this->ticketService->validateTicket($request->qr_hash, $request->user());
            return $this->successResponse(null, 'Ticket validated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode() ?: 400);
        }
    }

    public function transfer(Request $request, int $id)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            $this->ticketService->transferTicket($id, $request->email);
            return $this->successResponse(null, 'Ticket transferred successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function reissue(int $id)
    {
        try {
            $this->ticketService->reissueTicket($id);
            return $this->successResponse(null, 'Ticket reissued with new QR code');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function dynamicQr(int $id)
    {
        try {
            $qr = $this->ticketService->getDynamicQr($id);
            return $this->successResponse(['qr_hash' => $qr]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
