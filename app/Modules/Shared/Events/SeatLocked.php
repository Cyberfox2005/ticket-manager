<?php

namespace App\Modules\Shared\Events;

use App\Models\Reservation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SeatLocked
{
    use Dispatchable, SerializesModels;

    public function __construct(public Reservation $reservation) {}
}
