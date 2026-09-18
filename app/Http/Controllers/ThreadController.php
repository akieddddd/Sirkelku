<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Community;
use App\Models\Hobby;
use App\Models\Thread;
use App\Models\ThreadComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        $query = Thread::with(['user.school', 'community', 'hobby'])
            ->withCount('comments');

        // Filter by Hobby
        if ($request->filled('hobby')) {
            $query->where('hobby_id', $request->hobby);
        }

        // Search query
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Sorting: latest, trending (paling ramai), unanswered (belum terjawab)
        $sort = $request->get('sort', 'latest');
        if ($sort === 'trending') {
            $query->orderByDesc('is_pinned')->orderByDesc('comments_count');
        } elseif ($sort === 'unanswered') {
            $query->having('comments_count', '=', 0)->orderByDesc('is_pinned')->latest();
        } else {
            $query->orderByDesc('is_pinned')->latest();
        }

        $threads = $query->paginate(15)->withQueryString();

        $hobbies = Hobby::orderBy('name')->get();

        return view('threads.index', compact('threads', 'hobbies', 'sort'));
    }

    public function create(Request $request)
    {
        $hobbies = Hobby::orderBy('name')->get();
        $userCommunities = Auth::user()->communities;
        $selectedHobby = $request->get('hobby_id');
        $selectedCommunity = $request->get('community_id');

        return view('threads.create', compact('hobbies', 'userCommunities', 'selectedHobby', 'selectedCommunity'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'hobby_id' => ['required', 'exists:hobbies,id'],
            'community_id' => ['nullable', 'exists:communities,id'],
        ], [
            'title.required' => 'Judul topik diskusi wajib diisi.',
            'body.required' => 'Tuliskan isi permasalahan atau bahan obrolan.',
            'hobby_id.required' => 'Pilih kategori hobi yang sesuai.',
        ]);

        $thread = Thread::create([
            'user_id' => Auth::id(),
            'hobby_id' => $validated['hobby_id'],
            'community_id' => $validated['community_id'] ?? null,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_pinned' => false,
        ]);

        return redirect()->route('threads.show', $thread->id)->with('success', 'Utas diskusi berhasil dibuat di Tongkrongan.id!');
    }

    public function show(Thread $thread)
    {
        $thread->load(['user.school', 'community', 'hobby']);
        $thread->loadCount('comments');

        // Load root comments and nested replies
        $rootComments = $thread->rootComments;

        $user = Auth::user();
        $canPin = false;
        if ($user) {
            if ($thread->user_id === $user->id) {
                $canPin = true;
            } elseif ($thread->community && $thread->community->isAdmin($user)) {
                $canPin = true;
            }
        }

        return view('threads.show', compact('thread', 'rootComments', 'canPin'));
    }

    public function storeComment(Request $request, Thread $thread)
    {
        $validated = $request->validate([
            'comment_text' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:thread_comments,id'],
        ], [
            'comment_text.required' => 'Isi balasan tidak boleh kosong.',
        ]);

        $comment = ThreadComment::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'comment_text' => $validated['comment_text'],
        ]);

        // Send notification
        $sender = Auth::user();
        if ($comment->parent_id) {
            // Replying to a specific comment
            $parentComment = ThreadComment::find($comment->parent_id);
            if ($parentComment && $parentComment->user_id !== $sender->id) {
                AppNotification::create([
                    'user_id' => $parentComment->user_id,
                    'actor_id' => $sender->id,
                    'type' => 'thread_reply',
                    'title' => 'Balasan komentar di Tongkrongan.id',
                    'message' => $sender->name . ' membalas komentarmu pada utas "' . \Illuminate\Support\Str::limit($thread->title, 40) . '"',
                    'link_url' => route('threads.show', $thread->id) . '#comment-' . $comment->id,
                ]);
            }
        } elseif ($thread->user_id !== $sender->id) {
            // Replying to the thread root
            AppNotification::create([
                'user_id' => $thread->user_id,
                'actor_id' => $sender->id,
                'type' => 'thread_reply',
                'title' => 'Tanggapan baru di utasmu',
                'message' => $sender->name . ' merespons utasmu "' . \Illuminate\Support\Str::limit($thread->title, 40) . '"',
                'link_url' => route('threads.show', $thread->id) . '#comment-' . $comment->id,
            ]);
        }

        return redirect()->route('threads.show', $thread->id . '#comment-' . $comment->id)->with('success', 'Balasan berhasil dikirim!');
    }

    public function togglePin(Thread $thread)
    {
        $user = Auth::user();
        $canPin = ($thread->user_id === $user->id) || ($thread->community && $thread->community->isAdmin($user));

        if (!$canPin) {
            abort(403, 'Kamu tidak memiliki hak untuk menyematkan utas ini.');
        }

        $thread->is_pinned = !$thread->is_pinned;
        $thread->save();

        $status = $thread->is_pinned ? 'disematkan di bagian teratas!' : 'batal disematkan.';
        return redirect()->back()->with('success', 'Utas berhasil ' . $status);
    }
}
