<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;
use App\Services\PaymentService;

class CheckoutController extends Controller
{
    protected $orderService;
    protected $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
    }

    public function showShippingStep(Request $request)
    {
        $user = Auth::user();
        $shippingAddresses = $user->shippingAddresses;
        $prefillAddress = null;

        $sessionShippingData = $request->session()->get('checkout.shipping');

        if ($sessionShippingData) {
            if (isset($sessionShippingData['shipping_address_id'])) {
                // A saved address was selected in the previous step
                $prefillAddress = $shippingAddresses->firstWhere('id', $sessionShippingData['shipping_address_id']);
            } else {
                // A new address was entered in the previous step
                $prefillAddress = $sessionShippingData; // This will be an array
            }
        } else {
            // No session data, default to the first saved address if available
            $prefillAddress = $shippingAddresses->first();
        }

        return view('customer.checkout.shipping', compact('shippingAddresses', 'prefillAddress'));
    }

    public function processShippingStep(Request $request)
    {
        $rules = [
            'selected_address_id' => 'nullable|string',
        ];

        if ($request->input('selected_address_id') === 'new' || !$request->has('selected_address_id')) {
            $rules['address_line_1'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:255';
            $rules['postal_code'] = 'required|string|max:255';
        }

        $validated = $request->validate($rules);

        if (isset($validated['selected_address_id']) && $validated['selected_address_id'] !== 'new') {
            $address = Auth::user()->shippingAddresses()->findOrFail($validated['selected_address_id']);
            $request->session()->put('checkout.shipping', ['shipping_address_id' => $address->id]);
        } else {
            $request->session()->put('checkout.shipping', [
                'address_line_1' => $validated['address_line_1'],
                'city' => $validated['city'],
                'postal_code' => $validated['postal_code'],
            ]);
        }

        return redirect()->route('customer.checkout.payment');
    }

    public function showPaymentStep(Request $request)
    {
        if (!$request->session()->has('checkout.shipping')) {
            return redirect()->route('customer.checkout.shipping');
        }

        $cart = Auth::user()->cart;

        return view('customer.checkout.payment', compact('cart'));
    }

    public function processPaymentStep(Request $request)
    {
        if (!$request->session()->has('checkout.shipping')) {
            return redirect()->route('customer.checkout.shipping');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|in:stripe,paypal',
        ]);

        $user = Auth::user();

        $shippingData = $request->session()->get('checkout.shipping');

        $shippingAddressId = null;

        if (isset($shippingData['shipping_address_id'])) {
            // User selected an existing address
            $shippingAddressId = $shippingData['shipping_address_id'];
        } else {
            // User entered a new address, create it
            $shippingAddress = \App\Models\ShippingAddress::create([
                'user_id' => $user->id,
                'address_line_1' => $shippingData['address_line_1'],
                'city' => $shippingData['city'],
                'postal_code' => $shippingData['postal_code'],
            ]);
            $shippingAddressId = $shippingAddress->id;
        }

        $checkoutData = array_merge(
            ['name' => $user->name, 'email' => $user->email],
            $shippingData,
            $validated
        );

        $order = $this->orderService->createOrderFromCart($user, $checkoutData, $shippingAddressId);

        if (!$order) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $result = $this->paymentService->process($order, $checkoutData);

            $request->session()->forget('checkout');

            if (isset($result['redirect'])) {
                return redirect($result['redirect']);
            }

            return redirect()->route('customer.orders.show', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to process payment: ' . $e->getMessage());
        }
    }
}