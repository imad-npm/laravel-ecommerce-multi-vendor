<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Services\ConversationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function show(Store $store, ConversationService $conversationService)
    {
        $store->load(['products', 'user']);
        // Calculate average rating for all products in the store
        $avgRating = $store->averageRating();

        $conversation = null;
        if (Auth::check() && Auth::id() !== $store->user->id) {
            $conversation = $conversationService->findOrCreateConversation(Auth::user(), $store->user);
        }

        return view('stores.show', compact('store', 'avgRating', 'conversation'));
    }
}