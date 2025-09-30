<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function show(Store $store, ChatService $chatService)
    {
        $store->load(['products', 'user']);
        // Calculate average rating for all products in the store
        $avgRating = $store->averageRating();

        $conversation = null;
        if (Auth::check() && Auth::id() !== $store->user->id) {
            $conversation = $chatService->findOrCreateConversation(Auth::user(), $store->user);
        }

        return view('stores.show', compact('store', 'avgRating', 'conversation'));
    }
}