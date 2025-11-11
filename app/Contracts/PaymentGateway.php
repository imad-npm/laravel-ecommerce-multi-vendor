<?php

namespace App\Contracts;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface PaymentGateway
{
    public function process(Order $order, array $data): RedirectResponse|JsonResponse|array|null;
    public function handleCallback(Request $request, Order $order): RedirectResponse;
}