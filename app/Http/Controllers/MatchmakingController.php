<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Hobby;
use App\Models\MatchRequest;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchmakingController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $tab = $request->get('tab', 'discover'); // discover, incoming, outgoing, friends

        // Incoming requests
        $incomingRequests = MatchRequest::where('receiver_id', $currentUser->id)
            ->where('status', 'pending')
            ->with(['sender.school', 'sender.hobbies', 'hobby'])
            ->latest()
            ->get();

        // Outgoing requests
        $outgoingRequests = MatchRequest::where('sender_id', $currentUser->id)
            ->with(['receiver.school', 'receiver.hobbies', 'hobby'])
            ->latest()
            ->get();

        // Connected friends (status accepted)
        $connectedFriendIds = MatchRequest::where(function ($q) use ($currentUser) {
                $q->where('sender_id', $currentUser->id)->orWhere('receiver_id', $currentUser->id);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($req) use ($currentUser) {
                return $req->sender_id === $currentUser->id ? $req->receiver_id : $req->sender_id;
            })
            ->unique();

        $connectedFriends = User::whereIn('id', $connectedFriendIds)
            ->with(['school', 'hobbies'])
            ->get();

        // Discover partner query
        $pendingOrConnectedUserIds = MatchRequest::where(function ($q) use ($currentUser) {
                $q->where('sender_id', $currentUser->id)->orWhere('receiver_id', $currentUser->id);
            })
            ->pluck('sender_id')
            ->merge(MatchRequest::where(function ($q) use ($currentUser) {
                $q->where('sender_id', $currentUser->id)->orWhere('receiver_id', $currentUser->id);
            })->pluck('receiver_id'))
            ->unique();

        $query = User::where('id', '!=', $currentUser->id)
            ->whereNotNull('school_id')
            ->with(['school', 'hobbies']);

        // Filter by Hobby
        if ($request->filled('hobby')) {
            $query->whereHas('hobbies', function ($q) use ($request) {
                $q->where('hobbies.id', $request->hobby);
            });
        }

        // Filter by School
        if ($request->filled('school')) {
            $query->where('school_id', $request->school);
        }

        // Filter by City
        if ($request->filled('city')) {
            $query->whereHas('school', function ($q) use ($request) {
                $q->where('city', $request->city);
            });
        }

        $users = $query->paginate(12)->withQueryString();

        $hobbies = Hobby::orderBy('name')->get();
        $schools = School::orderBy('school_name')->get();
        $cities = School::select('city')->distinct()->whereNotNull('city')->pluck('city');

        return view('matchmaking.index', compact(
            'users',
            'incomingRequests',
            'outgoingRequests',
            'connectedFriends',
            'tab',
            'hobbies',
            'schools',
            'cities',
            'pendingOrConnectedUserIds'
        ));
    }

    public function sendRequest(Request $request)
    {
        $sender = Auth::user();

        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id', 'different:sender_id'],
            'hobby_id' => ['nullable', 'exists:hobbies,id'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validated['receiver_id'] == $sender->id) {
            return redirect()->back()->with('error', 'Kamu tidak bisa mengajak diri sendiri bermain.');
        }

        $existing = MatchRequest::where(function ($q) use ($sender, $validated) {
            $q->where('sender_id', $sender->id)->where('receiver_id', $validated['receiver_id']);
        })->orWhere(function ($q) use ($sender, $validated) {
            $q->where('sender_id', $validated['receiver_id'])->where('receiver_id', $sender->id);
        })->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return redirect()->back()->with('info', 'Kamu sudah berteman dengan pengguna ini!');
            }
            if ($existing->status === 'pending') {
                return redirect()->back()->with('info', 'Permintaan ajakan main sedang menunggu respons.');
            }
            // If rejected, update to pending
            $existing->update([
                'sender_id' => $sender->id,
                'receiver_id' => $validated['receiver_id'],
                'hobby_id' => $validated['hobby_id'] ?? null,
                'note' => $validated['note'] ?? null,
                'status' => 'pending',
            ]);
        } else {
            MatchRequest::create([
                'sender_id' => $sender->id,
                'receiver_id' => $validated['receiver_id'],
                'hobby_id' => $validated['hobby_id'] ?? null,
                'note' => $validated['note'] ?? null,
                'status' => 'pending',
            ]);
        }

        // Send notification
        $receiver = User::find($validated['receiver_id']);
        AppNotification::create([
            'user_id' => $receiver->id,
            'actor_id' => $sender->id,
            'type' => 'match_request',
            'title' => 'Ajakan Main Baru!',
            'message' => $sender->name . ' mengirimkan sinyal Ajak Main ke kamu: "' . ($validated['note'] ?? 'Yuk mabar / nongkrong bareng!') . '"',
            'link_url' => route('matchmaking.index', ['tab' => 'incoming']),
        ]);

        return redirect()->back()->with('success', 'Sinyal "Ajak Main" berhasil dikirim ke ' . $receiver->name . '!');
    }

    public function respondRequest(Request $request, MatchRequest $matchRequest)
    {
        $currentUser = Auth::user();

        if ($matchRequest->receiver_id !== $currentUser->id) {
            abort(403, 'Aksi ini hanya untuk penerima ajakan.');
        }

        $validated = $request->validate([
            'action' => ['required', 'in:accept,reject'],
        ]);

        if ($validated['action'] === 'accept') {
            $matchRequest->update(['status' => 'accepted']);

            // Send notification to sender
            AppNotification::create([
                'user_id' => $matchRequest->sender_id,
                'actor_id' => $currentUser->id,
                'type' => 'match_accepted',
                'title' => 'Ajakan Main Diterima!',
                'message' => $currentUser->name . ' menerima ajakan mainmu! Kalian sekarang resmi berteman.',
                'link_url' => route('matchmaking.index', ['tab' => 'friends']),
            ]);

            return redirect()->back()->with('success', 'Ajakan main diterima! Kamu dan ' . $matchRequest->sender->name . ' sekarang resmi berteman.');
        } else {
            $matchRequest->update(['status' => 'rejected']);
            return redirect()->back()->with('info', 'Ajakan main telah ditolak.');
        }
    }
}
