<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
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
        return match ($user->role) {
            UserRole::ADMIN => route('admin.dashboard'),
            UserRole::VENDOR => route('vendor.dashboard'),
            UserRole::CUSTOMER => route('customer.home'),
            default => '/',
        };
    }
}
