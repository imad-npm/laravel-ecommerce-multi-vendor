<?php

namespace App\Policies;

use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShippingAddressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ShippingAddress $shippingAddress): bool
    {
        return $user->id === $shippingAddress->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ShippingAddress $shippingAddress): bool
    {
        return $user->id === $shippingAddress->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ShippingAddress $shippingAddress): bool
    {
        return $user->id === $shippingAddress->user_id;
    }
}