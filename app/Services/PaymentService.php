<?php

namespace App\Services;

use App\Contracts\PaymentGatewayManager;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $paymentGatewayManager;

    public function __construct(PaymentGatewayManager $paymentGatewayManager)
    {
        $this->paymentGatewayManager = $paymentGatewayManager;
    }

    public function process(Order $order, array $paymentData): array
    {
        try {
            $gateway = $this->paymentGatewayManager->resolve($paymentData['payment_method']);
            Log::info('Processing payment for order ' . $order->id . ' with method ' . $paymentData['payment_method']);
            $result = $gateway->process($order, $paymentData);

            if ($result instanceof RedirectResponse) {
                return ['redirect' => $result->getTargetUrl()];
            }

            if (is_array($result)) {
                return $result;
            }

            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Payment processing failed for order ' . $order->id . ': ' . $e->getMessage());
            throw $e; // Re-throw the exception to be caught by the controller
        }
    }

    }