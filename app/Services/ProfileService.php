<?php

namespace App\Services;

use App\DataTransferObjects\Profile\UpdateProfileDTO;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileService
{
    public function updateProfile(User $user, UpdateProfileDTO $updateProfileDTO): void
    {
        if ($updateProfileDTO->name) {
            $user->name = $updateProfileDTO->name;
        }

        if ($updateProfileDTO->email && $user->email !== $updateProfileDTO->email) {
            $user->email = $updateProfileDTO->email;
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
