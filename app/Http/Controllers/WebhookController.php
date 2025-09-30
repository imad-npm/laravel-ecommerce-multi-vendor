<?php

namespace App\Http\Controllers;

use App\Services\PaymentGatewayResolver;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebhookController extends Controller
{
    protected $paymentGatewayResolver;

    public function __construct(PaymentGatewayResolver $paymentGatewayResolver)
    {
        $this->paymentGatewayResolver = $paymentGatewayResolver;
    }

    public function handle(Request $request, string $gatewayType)
    {
        try {
            $gateway = $this->paymentGatewayResolver->resolve($gatewayType);

            // Assuming each gateway has a handleWebhook method
            // and expects the raw request payload.
            // The specific implementation of handleWebhook will vary per gateway.
            if (method_exists($gateway, 'handleWebhook')) {
                $gateway->handleWebhook($request->all());
                return response()->json(['status' => 'success'], Response::HTTP_OK);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Webhook handler not found for this gateway.'], Response::HTTP_BAD_REQUEST);
            }
        } catch (\InvalidArgumentException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            // Log the exception for debugging
            \App\Exceptions\Handler::report($e);
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
