<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Community;
use App\Models\Hobby;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
use App\Services\ProfanityFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's joined community IDs
        $joinedCommunityIds = $user ? $user->communities()->pluck('communities.id') : collect();

        // Feed query: either public posts (no community), or posts from joined communities, or posts by user
        $query = Post::with(['user.school', 'community', 'hobby', 'likes', 'comments.user'])
            ->withCount(['likes', 'comments']);

        // Account Privacy (Instagram-style):
        // Guests only see public posts; logged-in users only see public posts, own posts, or posts from accepted friends
        $query->visibleTo($user);

        // Search query across posts, authors, hobbies, and communities
        if ($request->filled('q')) {
            $searchTerm = '%' . trim($request->q) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('content', 'like', $searchTerm)
                  ->orWhereHas('user', function ($uq) use ($searchTerm) {
                      $uq->where('name', 'like', $searchTerm)
                         ->orWhere('username', 'like', $searchTerm);
                  })
                  ->orWhereHas('hobby', function ($hq) use ($searchTerm) {
                      $hq->where('name', 'like', $searchTerm);
                  })
                  ->orWhereHas('community', function ($cq) use ($searchTerm) {
                      $cq->where('name', 'like', $searchTerm);
                  });
            });
        }

        // Optional filter by hobby
        if ($request->filled('hobby')) {
            $query->where('hobby_id', $request->hobby);
        }

        // Optional tab filter: all vs my_circles vs liked vs saved
        if ($request->get('tab') === 'my_circles' && $joinedCommunityIds->isNotEmpty()) {
            $query->whereIn('community_id', $joinedCommunityIds);
        } elseif ($request->get('tab') === 'liked' && $user) {
            $query->whereHas('likes', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } elseif ($request->get('tab') === 'saved') {
            $savedIds = [];
            if ($request->filled('saved_ids')) {
                $savedIds = array_filter(array_map('intval', explode(',', $request->get('saved_ids'))));
            }
            $query->whereIn('id', $savedIds);
        }

        // Guest feed paginates 15 (as explicitly requested in PRD Item 3); internal auth feed paginates 10
        $perPage = ! $user ? 15 : 10;
        $posts = $query->latest()->paginate($perPage)->withQueryString();

        $hobbies = Hobby::orderBy('name')->get();
        $userCommunities = $user ? $user->communities : collect();

        return view('feed.index', compact('posts', 'hobbies', 'userCommunities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => ['nullable', 'required_without:image', 'string', 'max:1000'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
            'hobby_id' => ['nullable', 'exists:hobbies,id'],
            'community_id' => ['nullable', 'exists:communities,id'],
        ], [
            'content.required_without' => 'Tulis sesuatu atau unggah foto untuk dibagikan!',
            'image.image' => 'File yang diunggah harus berupa gambar yang valid.',
            'image.mimes' => 'Format foto harus berupa JPG, JPEG, PNG, WEBP, atau GIF.',
            'image.max' => 'Ukuran foto maksimal 10 MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $rawContent = $validated['content'] ?? '';
        $hasProfanity = ProfanityFilter::contains($rawContent);
        $content = ProfanityFilter::censor($rawContent);

        $post = Post::create([
            'user_id' => Auth::id(),
            'community_id' => $validated['community_id'] ?? null,
            'hobby_id' => $validated['hobby_id'] ?? null,
            'content' => $content,
            'image_path' => $imagePath,
        ]);

        if ($hasProfanity) {
            AppNotification::create([
                'user_id' => Auth::id(),
                'actor_id' => null,
                'type' => 'censored',
                'title' => 'Konten Disensor Otomatis',
                'message' => 'Postinganmu mengandung kata yang disensor otomatis oleh sistem demi kenyamanan bersama: "' . \Illuminate\Support\Str::limit($content, 50) . '". Harap gunakan bahasa yang sopan ya!',
                'link_url' => route('feed.index') . '#post-' . $post->id,
            ]);
        }

        return redirect()->back()->with('success', 'Postingan berhasil diterbitkan.');
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Kamu tidak memiliki izin mengubah postingan ini.');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $rawContent = $validated['content'];
        $hasProfanity = ProfanityFilter::contains($rawContent);
        $content = ProfanityFilter::censor($rawContent);

        $post->update([
            'content' => $content,
        ]);

        if ($hasProfanity) {
            AppNotification::create([
                'user_id' => Auth::id(),
                'actor_id' => null,
                'type' => 'censored',
                'title' => 'Konten Disensor Otomatis',
                'message' => 'Pembaruan postinganmu mengandung kata yang disensor otomatis oleh sistem: "' . \Illuminate\Support\Str::limit($content, 50) . '". Harap gunakan bahasa yang sopan ya!',
                'link_url' => route('feed.index') . '#post-' . $post->id,
            ]);
        }

        return redirect()->back()->with('success', 'Postingan berhasil diperbarui.');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Kamu tidak memiliki izin menghapus postingan ini.');
        }

        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return redirect()->back()->with('success', 'Postingan berhasil dihapus.');
    }

    public function show(Request $request, Post $post)
    {
        $user = Auth::user();

        if (! $post->isVisibleTo($user)) {
            return redirect()->route('feed.index')->with('error', 'Postingan ini bersifat privat. Bertemanlah terlebih dahulu untuk melihat kiriman ini.');
        }

        return redirect()->to(route('feed.index') . '#post-' . $post->id);
    }

    public function toggleLike(Request $request, Post $post)
    {
        if (! $post->isVisibleTo(Auth::user())) {
            abort(403, 'Postingan ini bersifat privat.');
        }

        $userId = Auth::id();
        $existing = PostLike::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
            $isLiked = false;
        } else {
            PostLike::create([
                'post_id' => $post->id,
                'user_id' => $userId,
            ]);
            $isLiked = true;

            // Notification for post author
            if ($post->user_id !== $userId) {
                $actor = Auth::user();
                $postSnippet = !empty($post->content) ? '"' . \Illuminate\Support\Str::limit($post->content, 45) . '"' : 'unggahan foto';

                AppNotification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => $userId,
                    'type' => 'like',
                    'title' => $actor->name . ' menyukai postinganmu',
                    'message' => $actor->name . ' (@' . $actor->username . ') menyukai postinganmu: ' . $postSnippet,
                    'link_url' => route('feed.index') . '#post-' . $post->id,
                ]);
            }
        }

        $likesCount = $post->likes()->count();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'isLiked' => $isLiked,
                'likesCount' => $likesCount,
            ]);
        }

        return redirect()->back();
    }

    public function storeComment(Request $request, Post $post)
    {
        if (! $post->isVisibleTo(Auth::user())) {
            abort(403, 'Postingan ini bersifat privat.');
        }

        $validated = $request->validate([
            'comment_text' => ['required', 'string', 'max:500'],
        ], [
            'comment_text.required' => 'Komentar tidak boleh kosong.',
        ]);

        $rawComment = $validated['comment_text'];
        $hasProfanity = ProfanityFilter::contains($rawComment);
        $commentText = ProfanityFilter::censor($rawComment);

        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment_text' => $commentText,
        ]);

        $actor = Auth::user();

        // Notification for commenter if their comment was censored
        if ($hasProfanity) {
            AppNotification::create([
                'user_id' => Auth::id(),
                'actor_id' => null,
                'type' => 'censored',
                'title' => 'Komentar Disensor Otomatis',
                'message' => 'Komentarmu pada postingan mengandung kata yang disensor otomatis: "' . \Illuminate\Support\Str::limit($commentText, 50) . '". Harap gunakan bahasa yang sopan ya!',
                'link_url' => route('feed.index') . '#post-' . $post->id,
            ]);
        }

        // Notification for post author
        if ($post->user_id !== Auth::id()) {
            AppNotification::create([
                'user_id' => $post->user_id,
                'actor_id' => Auth::id(),
                'type' => 'comment',
                'title' => $actor->name . ' mengomentari postinganmu',
                'message' => $actor->name . ' (@' . $actor->username . '): "' . \Illuminate\Support\Str::limit($commentText, 50) . '"',
                'link_url' => route('feed.index') . '#post-' . $post->id,
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil dikirim.');
    }
}
