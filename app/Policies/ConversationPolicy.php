<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): ?bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        return null;
    }

    public function view(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->user_one_id || $user->id === $conversation->user_two_id;
    }

    public function send(User $user, Conversation $conversation): bool
    {
        return $user->id === $conversation->user_one_id || $user->id === $conversation->user_two_id;
    }
}
