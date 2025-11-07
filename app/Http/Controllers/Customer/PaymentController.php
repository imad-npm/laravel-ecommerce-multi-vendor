<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function create(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()->route('customer.orders.show', $order)->with('error', 'This order cannot be paid for.');
        }

        $user = auth()->user();
        $setupIntent = null;

        if (config('services.stripe.key')) {
            // Ensure the user has a Stripe customer ID
            if (!$user->stripe_id) {
                $user->createAsStripeCustomer();
            }
            $setupIntent = $user->createSetupIntent();
        }

        return view('customer.payments.create', compact('order', 'setupIntent'));
    }

    public function store(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()->route('customer.orders.show', $order)->with('error', 'This order cannot be paid for.');
        }

        $rules = [
            'payment_method' => 'required|string|in:stripe,paypal',
        ];

        if ($request->input('payment_method') === 'stripe') {
            $rules['payment_method_id'] = 'required|string';
        } elseif ($request->input('payment_method') === 'paypal') {
            $rules['paypal_order_id'] = 'required|string';
        }

        $validated = $request->validate($rules);

        try {
            $result = $this->paymentService->process($order, $validated);

            if (isset($result['redirect'])) {
                return redirect($result['redirect']);
            }

            return redirect()->route('customer.orders.show', $order)->with('success', 'Payment successful!');
        } catch (\Exception | \Stripe\Exception\CardException $e) {
            return redirect()->route('customer.orders.show', $order)->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}
