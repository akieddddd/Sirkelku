<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Post;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostReportController extends Controller
{
    /**
     * Laporkan postingan yang dianggap sensitif / tidak pantas.
     *
     * @param Request $request
     * @param int|string $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();

        // 1. Cek apakah user sudah pernah melaporkan postingan ini
        $alreadyReported = PostReport::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyReported) {
            $errorMessage = 'Sudah dilaporkan';

            if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => $errorMessage,
                ], 422);
            }

            return redirect()->back()->with('error', $errorMessage);
        }

        // 2. Validasi alasan pelaporan
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $reason = !empty($validated['reason']) 
            ? trim($validated['reason']) 
            : 'Konten tidak pantas atau melanggar pedoman komunitas';

        // 3. Simpan data ke post_reports
        PostReport::create([
            'post_id' => $post->id,
            'user_id' => $userId,
            'reason'  => $reason,
        ]);

        // 4. Increment reports_count pada tabel posts
        $post->increment('reports_count');
        $post->refresh();

        // 5. Jika reports_count >= 2, otomatis ubah is_flagged = true
        if ($post->reports_count >= 2 && !$post->is_flagged) {
            $post->forceFill(['is_flagged' => true])->save();

            AppNotification::create([
                'user_id' => $post->user_id,
                'actor_id' => null,
                'type' => 'flagged',
                'title' => 'Postingan Ditandai Sensitif',
                'message' => 'Postinganmu telah ditandai sensitif karena menerima beberapa laporan dari komunitas.',
                'link_url' => route('feed.index') . '#post-' . $post->id,
            ]);
        }

        $successMessage = 'Laporan berhasil dikirim. Terima kasih telah menjaga keamanan komunitas.';

        if ($request->wantsJson() || $request->ajax() || $request->expectsJson()) {
            return response()->json([
                'status'        => 'success',
                'message'       => $successMessage,
                'reports_count' => $post->reports_count,
                'is_flagged'    => (bool) $post->is_flagged,
            ]);
        }

        return redirect()->back()->with('success', $successMessage);
    }
}
