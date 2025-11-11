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

    public function create(Order $order)
    {
        $this->authorize('update', $order);

        if ($order->status !== 'pending') {
            return redirect()->route('customer.orders.show', $order)->with('error', 'This order cannot be paid for.');
        }

        $user = auth()->user();
        $paymentIntent = null;

        if (config('services.stripe.key')) {
            Stripe::setApiKey(config('services.stripe.secret'));

            if (!$user->stripe_id) {
                $customer = \Stripe\Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                ]);

                $user->stripe_id = $customer->id;
                $user->save();
            }

            $paymentIntent = \Stripe\PaymentIntent::create([
                'customer' => $user->stripe_id,
                'amount' => $order->total * 100,
                'currency' => 'usd',
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);
        } else {
            // Mock for testing if stripe key is not set
            $paymentIntent = (object)['client_secret' => 'mock_secret'];
        }

        return view('customer.payments.create', compact('order', 'paymentIntent'));
    }
}
