<?php

namespace App\Services;

use App\Models\User;
use App\Models\Product;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;

class ConversationService
{
    /**
     * Get all conversations for a user.
     *
     * @param User $user
     * @return Collection
     */
    public function getUserConversations(User $user): Collection
    {
        return Conversation::where(function ($query) use ($user) {
            $query->where('user_one_id', $user->id)
                  ->orWhere('user_two_id', $user->id);
        })
      //  ->whereHas('messages') // Ensure the conversation has at least one message
        ->with(['userOne', 'userTwo', 'product', 'messages' => function($query) {
            $query->latest()->take(1); // Get the last message for each conversation
        }])
        ->get();
    }

    public function findConversation(User $user1, User $user2, ?Product $product = null): ?Conversation
    {
        $userOneId = min($user1->id, $user2->id);
        $userTwoId = max($user1->id, $user2->id);

        return Conversation::where('user_one_id', $userOneId)
            ->where('user_two_id', $userTwoId)
            ->where('product_id', $product ? $product->id : null)
            ->first();
    }

    public function findOrCreateConversation(User $user1, User $user2, ?Product $product = null): Conversation
    {
        $userOneId = min($user1->id, $user2->id);
        $userTwoId = max($user1->id, $user2->id);

        return Conversation::firstOrCreate(
            [
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
                'product_id' => $product ? $product->id : null,
            ]
        );
    }
}
