<?php

namespace App\Modules\Shared\Events;

use App\Models\Ticket;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketValidated
{
    use Dispatchable, SerializesModels;

    public function __construct(public Ticket $ticket) {}
}
