<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use App\Models\Conversation;
use Illuminate\Database\Eloquent\Collection;

class MessageService
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
     * Create a new message in a conversation.
     *
     * @param User $sender
     * @param Conversation $conversation
     * @param string $content
     * @return Message
     */
    public function createMessage(User $sender, Conversation $conversation, string $content): Message
    {
        return $conversation->messages()->create([
            'sender_id' => $sender->id,
            'message' => $content,
        ]);
    }
}
