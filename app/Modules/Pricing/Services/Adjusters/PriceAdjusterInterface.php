<?php

namespace App\Modules\Pricing\Services\Adjusters;

use App\Models\User;
use App\Models\TicketType;

interface PriceAdjusterInterface
{
    public function adjust(float $currentPrice, TicketType $ticketType, ?User $user, array $context): float;
}
