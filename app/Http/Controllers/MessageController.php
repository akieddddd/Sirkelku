<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Find users with whom the auth user has exchanged messages
        $users = User::whereHas('sentMessages', function($q) use ($userId) {
                $q->where('receiver_id', $userId);
            })
            ->orWhereHas('receivedMessages', function($q) use ($userId) {
                $q->where('sender_id', $userId);
            })
            ->where('id', '!=', $userId)
            ->get();
            
        // Attach latest message and unread count for each conversation partner
        $users = $users->map(function($user) use ($userId) {
            $latestMessage = Message::where(function($q) use ($userId, $user) {
                    $q->where('sender_id', $userId)->where('receiver_id', $user->id);
                })
                ->orWhere(function($q) use ($userId, $user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $userId);
                })
                ->latest()
                ->first();

            $unreadCount = Message::where('sender_id', $user->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->count();

            $user->latest_message = $latestMessage;
            $user->unread_from_user = $unreadCount;
            return $user;
        })->sortByDesc(function($user) {
            return $user->latest_message ? $user->latest_message->created_at : null;
        });
        
        return view('messages.index', compact('users'));
    }

    public function show(User $user)
    {
        $authId = Auth::id();
        
        // Mark messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Also mark message notifications from this user as read
        AppNotification::where('user_id', $authId)
            ->where('actor_id', $user->id)
            ->where('type', 'message')
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        $messages = Message::where(function($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->orderBy('id', 'asc')
            ->get();
            
        return view('messages.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);
        
        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'content'     => $request->content
        ]);

        $message->load('sender');

        // Create in-app notification for recipient
        AppNotification::create([
            'user_id'  => $user->id,
            'actor_id' => Auth::id(),
            'type'     => 'message',
            'title'    => Auth::user()->name . ' mengirim pesan',
            'message'  => Str::limit($request->content, 60),
            'link_url' => route('messages.show', Auth::user()->username),
            'is_read'  => false,
        ]);

        // Broadcast to receiver's private channel
        try {
            broadcast(new \App\Events\MessageSent($message));
        } catch (\Throwable $e) {
            // Log but don't fail HTTP request if broadcast driver is not active
            report($e);
        }

        // If AJAX request (from real-time form), return JSON
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'         => 'success',
                'id'             => $message->id,
                'content'        => $message->content,
                'sender_id'      => $message->sender_id,
                'created_at_iso' => $message->created_at->toIso8601String(),
                'created_at'     => $message->created_at->format('H:i'),
            ]);
        }

        return redirect()->route('messages.show', $user->username);
    }

    /**
     * Poll / sync new messages in real-time for an active chat room
     */
    public function sync(Request $request, User $user)
    {
        $authId = Auth::id();
        $afterId = (int) $request->query('after_id', 0);

        // Mark any unread messages from this user as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $authId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Mark related notifications as read
        AppNotification::where('user_id', $authId)
            ->where('actor_id', $user->id)
            ->where('type', 'message')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function($q) use ($authId, $user) {
                $q->where('sender_id', $authId)->where('receiver_id', $user->id);
            })
            ->orWhere(function($q) use ($authId, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $authId);
            })
            ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status'   => 'success',
            'messages' => $messages->map(fn($m) => [
                'id'             => $m->id,
                'content'        => $m->content,
                'sender_id'      => $m->sender_id,
                'is_sender'      => $m->sender_id === $authId,
                'created_at_iso' => $m->created_at->toIso8601String(),
                'time'           => $m->created_at->format('H:i'),
                'read_at'        => $m->read_at ? ($m->read_at instanceof \Carbon\CarbonInterface ? $m->read_at->toIso8601String() : (string)$m->read_at) : null,
            ]),
        ]);
    }

    /**
     * Global incoming message & notification poller for navbar and floating toasts
     */
    public function checkIncoming(Request $request)
    {
        $authId = Auth::id();
        $sinceId = (int) $request->query('since_id', 0);

        $unreadQuery = Message::with('sender')
            ->where('receiver_id', $authId)
            ->whereNull('read_at');

        if ($sinceId > 0) {
            $unreadQuery->where('id', '>', $sinceId);
        }

        $incomingMessages = $unreadQuery->latest()->take(5)->get();

        return response()->json([
            'unread_messages_count'      => Message::where('receiver_id', $authId)->whereNull('read_at')->count(),
            'unread_notifications_count' => Auth::user()->unreadNotificationsCount(),
            'messages'                   => $incomingMessages->map(fn($m) => [
                'id'              => $m->id,
                'content'         => $m->content,
                'sender_name'     => $m->sender->name,
                'sender_username' => $m->sender->username,
                'sender_avatar'   => $m->sender->avatar_url,
                'chat_url'        => route('messages.show', $m->sender->username),
                'created_at_iso'  => $m->created_at->toIso8601String(),
            ]),
        ]);
    }
}
