<?php

namespace App\Modules\Tickets\Repositories;

use App\Models\Ticket;
use App\Modules\Shared\Repositories\BaseRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
    public function __construct(Ticket $model)
    {
        parent::__construct($model);
    }

    public function getTicketsForUser(User $user): Collection
    {
        return $user->tickets()
            ->with(['ticketType', 'order.event.venue'])
            ->latest()
            ->get();
    }

    public function findByQrHash(string $hash)
    {
        return $this->model->where('qr_hash', $hash)
            ->with(['user', 'ticketType', 'order.event'])
            ->first();
    }
}
