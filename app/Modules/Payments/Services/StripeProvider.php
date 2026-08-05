<?php

namespace App\Modules\Payments\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class StripeProvider implements PaymentGatewayInterface
{
    public function createPaymentIntent(Order $order): array
    {
        // Integration with Stripe SDK would go here
        return [
            'id' => 'pi_' . Str::random(24),
            'client_secret' => 'seti_' . Str::random(32),
            'amount' => $order->total_amount,
            'provider' => 'stripe'
        ];
    }

    public function capturePayment(string $externalId): bool
    {
        return true; // Mock success
    }

    public function refund(string $externalId, float $amount): bool
    {
        return true; // Mock success
    }
}
