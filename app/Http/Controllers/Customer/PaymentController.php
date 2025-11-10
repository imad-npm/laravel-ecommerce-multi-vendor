<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\SetupIntent;

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
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            if (!$user->stripe_id) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);

                $user->stripe_id = $customer->id;
                $user->save();
            }

            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $user->stripe_id,
            ]);
        } else {
            // Mock for testing if stripe key is not set
            $setupIntent = (object)['client_secret' => 'mock_secret'];
        }

        return view('customer.payments.create', compact('order', 'setupIntent'));
    }

    public function store(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()->route('customer.orders.show', $order)
                             ->with('error', 'This order cannot be paid for.');
        }

        $validated = $request->validate([
            'payment_method_id' => 'required|string',
        ]);

        // Use your PaymentService to process the Stripe payment
        $paymentData = array_merge($validated, ['payment_method' => 'stripe']);

        try {
            $result = $this->paymentService->process($order, $paymentData);

            if (isset($result['redirect'])) {
                return redirect($result['redirect']);
            }

            return redirect()->route('customer.orders.show', $order)
                             ->with('success', 'Payment successful!');
        } catch (\Exception | \Stripe\Exception\ApiErrorException $e) {
            return redirect()->route('customer.orders.show', $order)
                             ->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}
