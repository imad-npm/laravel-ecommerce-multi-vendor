<?php

namespace App\Policies;

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

    // Tu peux aussi ajouter d'autres méthodes plus tard (create, update, delete...)
}
