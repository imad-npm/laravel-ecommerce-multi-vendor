<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentGatewayManager;
use App\PaymentGateways\CardPaymentGateway;
use App\PaymentGateways\PaypalPaymentGateway;
use App\PaymentGateways\StripePaymentGateway;
use InvalidArgumentException;

class PaymentGatewayResolver implements PaymentGatewayManager
{
    public function resolve(string $method): PaymentGateway
    {
        return match ($method) {
            'card' => new CardPaymentGateway(),
            'paypal' => new PaypalPaymentGateway(),
            'stripe' => new StripePaymentGateway(),
            default => throw new InvalidArgumentException('Invalid payment method.'),
        };
    }
}