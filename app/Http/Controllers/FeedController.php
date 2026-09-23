<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Community;
use App\Models\Hobby;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostLike;
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

        // Optional filter by hobby
        if ($request->filled('hobby')) {
            $query->where('hobby_id', $request->hobby);
        }

        // Optional tab filter: all vs my_circles
        if ($request->get('tab') === 'my_circles' && $joinedCommunityIds->isNotEmpty()) {
            $query->whereIn('community_id', $joinedCommunityIds);
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

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

        $post = Post::create([
            'user_id' => Auth::id(),
            'community_id' => $validated['community_id'] ?? null,
            'hobby_id' => $validated['hobby_id'] ?? null,
            'content' => $validated['content'] ?? '',
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Postinganmu berhasil diterbitkan ke Nongkrong Yuk!');
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Kamu tidak memiliki izin mengubah postingan ini.');
        }

        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $post->update([
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'Postingan berhasil diperbarui!');
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

    public function toggleLike(Request $request, Post $post)
    {
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
                AppNotification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => $userId,
                    'type' => 'like',
                    'title' => 'Seseorang menyukai postinganmu',
                    'message' => Auth::user()->name . ' menyukai postinganmu di Nongkrong Yuk.',
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
        $validated = $request->validate([
            'comment_text' => ['required', 'string', 'max:500'],
        ], [
            'comment_text.required' => 'Komentar tidak boleh kosong.',
        ]);

        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'comment_text' => $validated['comment_text'],
        ]);

        // Notification for post author
        if ($post->user_id !== Auth::id()) {
            AppNotification::create([
                'user_id' => $post->user_id,
                'actor_id' => Auth::id(),
                'type' => 'comment',
                'title' => 'Komentar baru pada postinganmu',
                'message' => Auth::user()->name . ' mengomentari postinganmu: "' . \Illuminate\Support\Str::limit($validated['comment_text'], 40) . '"',
                'link_url' => route('feed.index') . '#post-' . $post->id,
            ]);
        }

        return redirect()->back()->with('success', 'Komentar berhasil dikirim!');
    }
}
