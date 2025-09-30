<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\ProcessPaymentRequest;
use App\Services\PaymentGatewayResolver;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    // ... (existing methods)

    // PayPal Payment
    protected $paymentGatewayResolver;

    public function __construct(PaymentGatewayResolver $paymentGatewayResolver)
    {
        $this->paymentGatewayResolver = $paymentGatewayResolver;
    }

    public function showRetry(Order $order)
    {
        $this->authorize('retry', $order);
 
        return view('customer.checkout.payment', compact('order'));
    }

    public function retry(ProcessPaymentRequest $request, Order $order)
    {
        $this->authorize('retry', $order);

        $validated = $request->validated();

        return $this->processPayment($order, $validated);
    }

    public function processPayment(Order $order, array $paymentData): JsonResponse|RedirectResponse
    {
        try {
            $gateway = $this->paymentGatewayResolver->resolve($paymentData['payment_method']);
            $response = $gateway->process($order, $paymentData);

            if ($response instanceof JsonResponse) {
                return $response;
            } elseif ($response instanceof RedirectResponse) {
                return $response;
            } else {
                // Default redirect if no specific response is returned by the gateway
                return redirect()->route('customer.orders.show', $order)->with('success', 'Payment initiated.');
            }
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('customer.orders.show', $order)->with('error', 'Payment processing failed. Please try again.');
        }
    }

    public function handleCallback(Request $request, Order $order, string $gatewayType)
    {
        try {
            $gateway = $this->paymentGatewayResolver->resolve($gatewayType);
            if (method_exists($gateway, 'handleCallback')) {
                return $gateway->handleCallback($request, $order);
            } else {
                return redirect()->route('customer.orders.show', $order)->with('error', 'Callback handler not found for this gateway.');
            }
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('customer.orders.show', $order)->with('error', 'Payment callback failed. Please try again.');
        }
    }
}