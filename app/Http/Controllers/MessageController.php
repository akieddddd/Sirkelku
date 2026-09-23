<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get latest message for each conversation
        // MySQL requires group by tricks, this is a simpler approach: get users you have messaged with
        $users = User::whereHas('sentMessages', function($q) use ($userId) {
                $q->where('receiver_id', $userId);
            })
            ->orWhereHas('receivedMessages', function($q) use ($userId) {
                $q->where('sender_id', $userId);
            })
            ->where('id', '!=', $userId)
            ->get();
            
        // You could sort them by latest message here in a real app
        
        return view('messages.index', compact('users'));
    }

    public function show(User $user)
    {
        $authId = Auth::id();
        
        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        $messages = Message::where(function($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);
        
        $message = Message::create([
            'sender_id'  => Auth::id(),
            'receiver_id' => $user->id,
            'content'    => $request->content
        ]);

        $message->load('sender');

        // Broadcast to receiver's private channel
        broadcast(new \App\Events\MessageSent($message));

        // If AJAX request (from real-time form), return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'id'         => $message->id,
                'content'    => $message->content,
                'sender_id'  => $message->sender_id,
                'created_at' => $message->created_at->format('H:i'),
            ]);
        }

        return redirect()->route('messages.show', $user->username);
    }
}
