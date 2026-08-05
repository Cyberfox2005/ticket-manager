<?php

namespace App\Modules\Pricing\Services\Adjusters;

use App\Models\User;
use App\Models\TicketType;
use Illuminate\Support\Carbon;

class EarlyBirdAdjuster implements PriceAdjusterInterface
{
    public function adjust(float $currentPrice, TicketType $ticketType, ?User $user, array $context): float
    {
        if ($ticketType->sales_start_at && Carbon::now()->diffInDays($ticketType->sales_start_at) < 7) {
            // 10% discount if within first 7 days
            return $currentPrice * 0.9;
        }
        return $currentPrice;
    }
}
