<?php

namespace App\Modules\Payments\Services;

use App\Models\Order;

interface PaymentGatewayInterface
{
    public function createPaymentIntent(Order $order): array;
    public function capturePayment(string $externalId): bool;
    public function refund(string $externalId, float $amount): bool;
}
