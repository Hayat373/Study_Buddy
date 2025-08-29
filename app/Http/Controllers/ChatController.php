<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all chats for the user
        $chats = Chat::where('user1_id', $user->id)
                    ->orWhere('user2_id', $user->id)
                    ->with(['user1', 'user2', 'messages' => function($query) {
                        $query->latest()->limit(1);
                    }])
                    ->get()
                    ->map(function($chat) use ($user) {
                        $chat->other_user = $chat->user1_id == $user->id ? $chat->user2 : $chat->user1;
                        $chat->unread_count = $chat->messages()
                            ->where('sender_id', '!=', $user->id)
                            ->where('is_read', false)
                            ->count();
                        return $chat;
                    });
        
        return view('chat.index', compact('chats'));
    }

    public function show(Chat $chat)
    {
        $user = Auth::user();
        
        // Check if user is part of this chat
        if ($chat->user1_id != $user->id && $chat->user2_id != $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $otherUser = $chat->user1_id == $user->id ? $chat->user2 : $chat->user1;
        
        // Mark messages as read
        Message::where('chat_id', $chat->id)
                ->where('sender_id', '!=', $user->id)
                ->update(['is_read' => true]);
        
        $messages = $chat->messages()->with('sender')->latest()->paginate(20);
        
        return view('chat.show', compact('chat', 'otherUser', 'messages'));
    }

    public function storeMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        $user = Auth::user();
        
        // Check if user is part of this chat
        if ($chat->user1_id != $user->id && $chat->user2_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $message = $chat->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);
        
        // Load sender relationship for the response
        $message->load('sender');
        
        return response()->json($message);
    }

    public function findOrCreate(User $user)
    {
        $currentUser = Auth::user();
        
        if ($currentUser->id === $user->id) {
            return redirect()->route('chat.index')->with('error', 'You cannot chat with yourself.');
        }
        
        $chat = Chat::where(function($query) use ($currentUser, $user) {
            $query->where('user1_id', $currentUser->id)
                  ->where('user2_id', $user->id);
        })->orWhere(function($query) use ($currentUser, $user) {
            $query->where('user1_id', $user->id)
                  ->where('user2_id', $currentUser->id);
        })->first();
        
        if (!$chat) {
            $chat = Chat::create([
                'user1_id' => $currentUser->id,
                'user2_id' => $user->id,
            ]);
        }
        
        return redirect()->route('chat.show', $chat);
    }
    public function userSearch(Request $request)
{
    $query = $request->get('query');
    $currentUser = auth()->user();
    
    $users = User::where('id', '!=', $currentUser->id)
                ->where(function($q) use ($query) {
                    $q->where('username', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%")
                      ->orWhere('name', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get();
    
    return response()->json($users);
}

public function startChat(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);
    
    $otherUser = User::findOrFail($request->user_id);
    return $this->findOrCreate($otherUser);
}
}