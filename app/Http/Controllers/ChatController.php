<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Conversation;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Display a listing of the user's conversations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = $this->chatService->getUserConversations($user);

        return view('chat.index', compact('conversations'));
    }

    /**
     * Display messages for a specific conversation.
     *
     * @param Conversation $conversation
     * @return \Illuminate\View\View
     */
    public function show(Conversation $conversation)
    {
        $messages = $this->chatService->getMessages($conversation);

        // Determine the other participant for display purposes
        $otherUser = ($conversation->user_one_id === Auth::id()) ? $conversation->userTwo : $conversation->userOne;

        return view('chat.show', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Send a new message or initiate a conversation.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $sender = Auth::user();
        $receiver = User::findOrFail($request->receiver_id);
        $product = $request->product_id ? Product::findOrFail($request->product_id) : null;

        $conversation = $this->chatService->sendMessage($sender, $receiver, $request->message, $product);

        // Redirect back to the conversation view
        return redirect()->route('chat.show', [
            'conversation' => $conversation->id,
        ]);
    }
}