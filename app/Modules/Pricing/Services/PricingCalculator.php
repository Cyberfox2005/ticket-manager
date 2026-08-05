<?php

namespace App\Modules\Pricing\Services;

use App\Models\User;
use App\Models\TicketType;
use App\Modules\Pricing\Services\Adjusters\PriceAdjusterInterface;

class PricingCalculator
{
    /** @var PriceAdjusterInterface[] */
    protected array $adjusters = [];

    public function addAdjuster(PriceAdjusterInterface $adjuster): void
    {
        $this->adjusters[] = $adjuster;
    }

    public function calculate(TicketType $ticketType, ?User $user = null, array $context = []): array
    {
        $basePrice = (float) $ticketType->price;
        $currentPrice = $basePrice;
        $breakdown = [['label' => 'Base Price', 'amount' => $basePrice]];

        foreach ($this->adjusters as $adjuster) {
            $newPrice = $adjuster->adjust($currentPrice, $ticketType, $user, $context);
            if ($newPrice !== $currentPrice) {
                $diff = $newPrice - $currentPrice;
                $breakdown[] = [
                    'label' => (new \ReflectionClass($adjuster))->getShortName(),
                    'amount' => $diff
                ];
                $currentPrice = $newPrice;
            }
        }

        // Final Taxes & Fees (Simplified for this phase)
        $serviceFee = 2.50; // Example flat fee
        $breakdown[] = ['label' => 'Service Fee', 'amount' => $serviceFee];
        $total = $currentPrice + $serviceFee;

        return [
            'total' => round($total, 2),
            'currency' => 'USD',
            'breakdown' => $breakdown
        ];
    }
}
