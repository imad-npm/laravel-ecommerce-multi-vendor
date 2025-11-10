<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreMessageRequest;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index(Conversation $conversation)
    {
        $messages = $this->messageService->getMessages($conversation);
        $otherUser = ($conversation->user_one_id === Auth::id()) ? $conversation->userTwo : $conversation->userOne;

        return view('messages.index', compact('conversation', 'messages', 'otherUser'));
    }

    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
        $sender = Auth::user();
        
        $this->messageService->createMessage($sender, $conversation, $request->message);

        return redirect()->route('conversations.messages.index', $conversation);
    }
}
