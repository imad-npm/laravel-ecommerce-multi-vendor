<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\OAuth;
use Stripe\Stripe;
use Stripe\Account;

class StripeAccountController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $stripeAccount = null;

        if ($user->stripe_account_id) {
            try {
                Stripe::setApiKey(config('services.stripe.secret'));
                $stripeAccount = Account::retrieve($user->stripe_account_id);
            } catch (\Exception $e) {
                Log::error('Stripe account retrieval failed for user ' . $user->id . ': ' . $e->getMessage());
                // Optionally, handle the error in the view
            }
        }

        return view('vendor.stripe.index', compact('stripeAccount'));
    }

    public function connect()
    {
        $user = Auth::user();
        if ($user->stripe_account_id) {
            return redirect()->route('vendor.dashboard')
                ->with('info', 'Your Stripe account is already linked.');
        }

        // Generate a unique state parameter to prevent CSRF attacks
        $state = bin2hex(random_bytes(16));
        session(['stripe_oauth_state' => $state]);

        $authorizeUrl = OAuth::authorizeUrl([
            'scope' => 'read_write', // Request necessary permissions
            'client_id' => config('services.stripe.client_id'),
            'redirect_uri' => route('vendor.stripe.callback'),
            'state' => $state,
            'stripe_user' => [
                'email' => $user->email,
                'country' => 'US', // Default or get from user profile
            ],
        ]);

        return redirect($authorizeUrl);
    }

    public function callback(Request $request)
    {
        $user = Auth::user();

        // Verify the state parameter to prevent CSRF attacks
        if ($request->input('state') !== session('stripe_oauth_state')) {
            Log::error('Stripe OAuth state mismatch for user: ' . $user->id);
            return redirect()->route('vendor.dashboard')->with('error', 'Stripe connection failed due to security reasons.');
        }

        // Handle errors from Stripe
        if ($request->has('error')) {
            Log::error('Stripe OAuth error for user ' . $user->id . ': ' . $request->input('error_description'));
            return redirect()->route('vendor.dashboard')->with('error', 'Stripe connection failed: ' . $request->input('error_description'));
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $response = OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $request->input('code'),
            ]);

            $stripeAccountId = $response->stripe_user_id;

            $user->stripe_account_id = $stripeAccountId;
            $user->save();

            return redirect()->route('vendor.dashboard')->with('success', 'Stripe account connected successfully!');
        } catch (\Exception $e) {
            Log::error('Stripe OAuth token exchange failed for user ' . $user->id . ': ' . $e->getMessage());
            return redirect()->route('vendor.dashboard')->with('error', 'Failed to connect Stripe account: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        $user = Auth::user();
        $user->stripe_account_id = null;
        $user->save();

        return redirect()->route('vendor.dashboard')->with('success', 'Stripe account disconnected successfully.');
    }
}
