<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Message\StoreMessageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MessageController extends Controller
{
    use AuthorizesRequests;
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $messages = $this->messageService->getMessages($conversation);
        $otherUser = ($conversation->user_one_id === Auth::id()) ? $conversation->userTwo : $conversation->userOne;

        return view('messages.index', compact('conversation', 'messages', 'otherUser'));
    }

    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
        $this->authorize('send', $conversation);
        $sender = Auth::user();
        
        $this->messageService->createMessage($sender, $conversation, $request->message);

        return redirect()->route('conversations.messages.index', $conversation);
    }
}
