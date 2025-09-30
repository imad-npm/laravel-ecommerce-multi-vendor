<?php

namespace App\Contracts;

interface PaymentGatewayManager
{
    public function resolve(string $method): PaymentGateway;
}