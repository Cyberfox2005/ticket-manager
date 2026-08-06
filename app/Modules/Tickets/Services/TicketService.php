<?php

namespace App\Modules\Tickets\Services;

use App\Modules\Tickets\Repositories\TicketRepositoryInterface;
use App\Modules\Tickets\Services\SecurityService;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        protected TicketRepositoryInterface $ticketRepository,
        protected SecurityService $securityService
    ) {}

    public function getUserTickets(User $user)
    {
        return $this->ticketRepository->getTicketsForUser($user);
    }

    /**
     * Enterprise Feature: Dynamic QR Generation
     */
    public function getDynamicQr(int $ticketId): string
    {
        $ticket = $this->ticketRepository->find($ticketId);

        $payload = [
            'tid' => $ticket->id,
            'uid' => $ticket->user_id,
            'nonce' => Str::random(10),
            'ts' => time()
        ];

        $signed = $this->securityService->signPayload($payload);

        // Add dynamic rotation layer
        return $this->securityService->generateDynamicToken($signed);
    }

    /**
     * Ticket Lifecycle: Transfer
     */
    public function transferTicket(int $ticketId, string $recipientEmail)
    {
        $ticket = $this->ticketRepository->find($ticketId);
        $recipient = User::where('email', $recipientEmail)->firstOrFail();

        return DB::transaction(function () use ($ticket, $recipient) {
            // 1. Invalidate old QR hash
            $newHash = Str::uuid()->toString();

            // 2. Transfer ownership
            return $this->ticketRepository->update($ticket->id, [
                'user_id' => $recipient->id,
                'qr_hash' => $newHash,
                'validated_at' => null // Reset if it was somehow scanned
            ]);
        });
    }

    /**
     * Ticket Lifecycle: Reissue (Fraud Protection)
     */
    public function reissueTicket(int $ticketId)
    {
        $ticket = $this->ticketRepository->find($ticketId);
        return $this->ticketRepository->update($ticket->id, [
            'qr_hash' => Str::uuid()->toString()
        ]);
    }

    public function validateTicket(string $qrHash, User $validator)
    {
        // Advanced Validation: Check Redis Bloom Filter for duplicates (Phase 3 optimization)

        $ticket = $this->ticketRepository->findByQrHash($qrHash);

        if (! $ticket) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Invalid Ticket');
        }

        if ($ticket->validated_at) {
            throw new \InvalidArgumentException('Ticket already used at ' . $ticket->validated_at->toDateTimeString());
        }

        $event = $ticket->order->event;
        if (Carbon::now()->isBefore($event->start_at->subHours(2))) {
            throw new \InvalidArgumentException('Event has not started yet');
        }

        $updated = $this->ticketRepository->update($ticket->id, [
            'validated_at' => Carbon::now(),
            'validated_by' => $validator->id,
        ]);

        if ($updated) {
            \App\Modules\Shared\Events\TicketValidated::dispatch($ticket->fresh());
        }

        return $updated;
    }
}
