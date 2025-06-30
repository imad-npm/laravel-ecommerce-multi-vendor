<?php

namespace App\Services;

use App\DataTransferObjects\UserData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorProfileService
{
    public function updateProfile(User $user, UserData $userData): void
    {
        $user->name = $userData->name;
        $user->email = $userData->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    public function deleteAccount(User $user, Request $request): void
    {
        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
