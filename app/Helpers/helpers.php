<?php

use App\Enums\UserRole;

if (! function_exists('getUserHomeRoute')) {
    /**
     * Get the redirect route for an authenticated user based on their role.
     *
     * @param \App\Models\User $user
     * @return string
     */
    function getUserHomeRoute()
    {
        $user=auth()->user() ;
       return match ($user?->role) {
            UserRole::ADMIN => route('admin.dashboard'),
            UserRole::VENDOR => route('vendor.dashboard'),
            UserRole::CUSTOMER => route('customer.home'),
            default => '/',
        };
    }
}
