<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($this->redirectRoute($user) . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended($this->redirectRoute($user) . '?verified=1');
    }

    private function redirectRoute($user): string
    {
        return match ($user->role ?? 'guest') {
            'admin' => route('admin.dashboard'),
            'vendor' => route('vendor.dashboard'),
            'customer' => route('customer.home'),
            default => '/',
        };
    }
}
