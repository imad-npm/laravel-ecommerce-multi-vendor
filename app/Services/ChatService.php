<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use App\Models\Product;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;

class ChatService
{
    /**
     * Get messages for a specific conversation.
     *
     * @param Conversation $conversation
     * @return Collection
     */
    public function getMessages(Conversation $conversation): Collection
    {
        return $conversation->messages()->orderBy('created_at')->get();
    }

    /**
     * Send a message.
     *
     * @param User $sender
     * @param User $receiver
     * @param string $content
     * @param Product|null $product
     * @return Message
     */
    public function sendMessage(User $sender, User $receiver, string $content, ?Product $product = null): Message
    {
        // Ensure user_one_id is always the smaller ID to maintain uniqueness
        $userOneId = min($sender->id, $receiver->id);
        $userTwoId = max($sender->id, $receiver->id);

        $conversation = Conversation::firstOrCreate(
            [
                'user_one_id' => $userOneId,
                'user_two_id' => $userTwoId,
                'product_id' => $product ? $product->id : null,
            ]
        );

        return $conversation->messages()->create([
            'sender_id' => $sender->id,
            'message' => $content,
        ]);
    }

    /**
     * Get all conversations for a user.
     *
     * @param User $user
     * @return Collection
     */
    public function getUserConversations(User $user): Collection
    {
        return Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo', 'product', 'messages' => function($query) {
                $query->latest()->take(1); // Get the last message for each conversation
            }])
            ->get();
    }

    /**
     * Find or create a conversation.
     *
     * @param User $user1
     * @param User $user2
     * @param Product|null $product
     * @return Conversation
     */
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
