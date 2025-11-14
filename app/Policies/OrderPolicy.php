<?php

namespace App\Policies;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * L'utilisateur peut-il voir cette commande ?
     */
    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    public function retry(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->status === OrderStatus::PENDING;
    }

    public function cancel(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->status === OrderStatus::PENDING;
    }

    public function update(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }

    // Tu peux aussi ajouter d'autres méthodes plus tard (create, update, delete...)
}
