<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Services\ConversationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Conversation\StoreConversationRequest;
use App\Models\Conversation;

class ConversationController extends Controller
{
    protected $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->conversationService = $conversationService;
    }

    public function index()
    {
        $user = Auth::user();
        $conversations = $this->conversationService->getUserConversations($user);

        return view('conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // This can be used to show conversation details, but not messages.
        // Messages will be handled by MessageController.
        $otherUser = ($conversation->user_one_id === Auth::id()) ? $conversation->userTwo : $conversation->userOne;
        return view('conversations.show', compact('conversation', 'otherUser'));
    }

    public function store(StoreConversationRequest $request)
    {
        $user1 = Auth::user();
        $user2 = User::findOrFail($request->user_id);
        $product = $request->product_id ? Product::findOrFail($request->product_id) : null;

        $conversation = $this->conversationService->findOrCreateConversation($user1, $user2, $product);

        return redirect()->route('conversations.messages.index', $conversation);
    }
}
