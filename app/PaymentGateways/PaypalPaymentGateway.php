<?php

namespace App\PaymentGateways;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PaypalPaymentGateway implements \App\Contracts\PaymentGateway
{
    private $payPalClient;

    public function __construct()
    {
        $this->payPalClient = $this->createPayPalClient();
    }

    private function createPayPalClient()
    {
        $config = config('services.paypal');
        if ($config['mode'] === 'live') {
            $environment = new ProductionEnvironment($config['client_id'], $config['secret']);
        } else {
            $environment = new SandboxEnvironment($config['client_id'], $config['secret']);
        }
        return new PayPalHttpClient($environment);
    }

    public function process(Order $order, array $data): ?RedirectResponse
    {
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_gateway' => 'paypal',
            'amount' => $order->total,
            'currency' => 'USD',
            'status' => 'initiated',
        ]);

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $order->id,
                "amount" => [
                    "value" => $order->total_amount,
                    "currency_code" => "USD"
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('customer.orders.show', $order),
                "return_url" => route('customer.payment.paypal.callback', ['order_id' => $order->id, 'payment_id' => $payment->id])
            ]
        ];

        try {
            $response = $this->payPalClient->execute($request);
            if ($response->statusCode == 201) {
                $payment->transaction_id = $response->result->id;
                $payment->save();
                foreach ($response->result->links as $link) {
                    if ($link->rel == 'approve') {
                        return redirect()->away($link->href);
                    }
                }
            }
        } catch (\Exception $e) {
            $payment->status = 'failed';
            $payment->error_message = $e->getMessage();
            $payment->save();
            return redirect()->route('customer.orders.show', $order)->with('error', 'Could not connect to PayPal.');
        }

        $payment->status = 'failed';
        $payment->error_message = 'Something went wrong with PayPal.';
        $payment->save();
        return redirect()->route('customer.orders.show', $order)->with('error', 'Something went wrong with PayPal.');
    }

    public function handleCallback(Request $request, Order $order): RedirectResponse
    {
        $paymentId = $request->input('payment_id');
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return redirect()->route('customer.orders.show', $order)->with('error', 'Payment record not found.');
        }

        $captureRequest = new OrdersCaptureRequest($request->input('token'));

        try {
            $response = $this->payPalClient->execute($captureRequest);
            if ($response->statusCode == 201 && $response->result->status == 'COMPLETED') {
                $payment->status = 'succeeded';
                $payment->transaction_id = $response->result->id; // Update with actual transaction ID from PayPal
                $payment->save();

                $order->status = 'paid';
                $order->save();
                event(new \App\Events\OrderPaid($order));

                return redirect()->route('customer.orders.show', $order)->with('success', 'Payment successful!');
            }
        } catch (\Exception $e) {
            $payment->status = 'failed';
            $payment->error_message = $e->getMessage();
            $payment->save();
            return redirect()->route('customer.orders.show', $order)->with('error', 'PayPal payment failed.');
        }

        $payment->status = 'failed';
        $payment->error_message = 'PayPal payment failed.';
        $payment->save();
        return redirect()->route('customer.orders.show', $order)->with('error', 'PayPal payment failed.');
    }
}
