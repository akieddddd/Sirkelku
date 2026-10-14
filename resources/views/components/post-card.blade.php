@props([
    'post'
])

<article id="post-{{ $post->id }}" 
    class="border-b border-slate-200 dark:border-zinc-800 p-4 hover:bg-slate-50/50 dark:hover:bg-zinc-900/40 transition-colors duration-150 flex gap-3 group"
    x-data="{ 
        isLiked: {{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}, 
        likesCount: {{ $post->likes_count }},
        isSaved: false,
        showComments: false,
        showEditModal: false,
        showReportModal: false,
        showDetailModal: false,
        isFlagged: {{ $post->is_flagged ? 'true' : 'false' }},
        showFlaggedContent: false,
        reportReason: '',
        reportCustomReason: '',
        isReporting: false,
        hasReported: {{ Auth::check() && $post->isReportedBy(Auth::id()) ? 'true' : 'false' }},
        init() {
            try {
                const saved = JSON.parse(localStorage.getItem('sirkelku_saved_posts') || '[]');
                this.isSaved = saved.includes({{ $post->id }});
            } catch(e) {}
        },
        toggleBookmark() {
            @guest
                $dispatch('open-auth-modal', { message: 'Masuk ke Sirkelku dulu yuk buat like, komentar, atau simpan postingan ini!' });
                return;
            @endguest
            try {
                let saved = JSON.parse(localStorage.getItem('sirkelku_saved_posts') || '[]');
                if (saved.includes({{ $post->id }})) {
                    saved = saved.filter(id => id !== {{ $post->id }});
                    this.isSaved = false;
                    if (typeof showToast === 'function') showToast('Postingan dihapus dari bookmark.');
                } else {
                    saved.push({{ $post->id }});
                    this.isSaved = true;
                    if (typeof showToast === 'function') showToast('Postingan disimpan ke bookmark!');
                }
                localStorage.setItem('sirkelku_saved_posts', JSON.stringify(saved));
            } catch(e) {}
        },
        toggleLike() {
            @guest
                $dispatch('open-auth-modal', { message: 'Masuk ke Sirkelku dulu yuk buat like, komentar, atau simpan postingan ini!' });
                return;
            @endguest
            fetch('{{ route('posts.like', $post->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.isLiked = data.isLiked;
                this.likesCount = data.likesCount;
            });
        },
        submitReport() {
            if (!this.reportReason || this.isReporting) return;
            this.isReporting = true;
            const finalReason = (this.reportReason === 'Lainnya' && this.reportCustomReason.trim()) 
                ? this.reportCustomReason.trim() 
                : this.reportReason;

            fetch('{{ route('posts.report', $post->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reason: finalReason })
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok) {
                    this.hasReported = true;
                    this.showReportModal = false;
                    if (data.is_flagged) {
                        this.isFlagged = true;
                    }
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Laporan berhasil dikirim.');
                    } else {
                        alert(data.message || 'Laporan berhasil dikirim.');
                    }
                } else {
                    this.showReportModal = false;
                    if (typeof showToast === 'function') {
                        showToast(data.message || 'Sudah dilaporkan');
                    } else {
                        alert(data.message || 'Sudah dilaporkan');
                    }
                }
            })
            .catch(() => {
                alert('Terjadi kesalahan saat mengirim laporan.');
            })
            .finally(() => {
                this.isReporting = false;
            });
        }
    }">
    
    <!-- User Avatar Column -->
    <a href="{{ route('profile.show', $post->user->username) }}" class="shrink-0 mt-0.5">
        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" 
            class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-200 dark:ring-zinc-700 group-hover:ring-[#FF4500]/50 transition-all">
    </a>

    <!-- Main Content Column -->
    <div class="flex-1 min-w-0">
        <!-- Post Header (Clean 1-line layout) -->
        <div class="flex items-start justify-between gap-2">
            <div class="flex items-center gap-1.5 min-w-0 flex-wrap">
                <!-- Name -->
                <a href="{{ route('profile.show', $post->user->username) }}" 
                   class="font-black text-slate-800 dark:text-zinc-100 hover:text-[#FF4500] dark:hover:text-[#FF4500] hover:underline text-sm truncate transition-colors max-w-[150px] sm:max-w-[200px]">
                    {{ $post->user->name }}
                </a>
                
                <!-- School Badge -->
                @if($post->user->school)
                    <span class="hidden sm:inline-flex items-center text-[11px] font-semibold text-slate-500 dark:text-zinc-400 bg-slate-100 dark:bg-zinc-800 px-2 py-0.5 rounded-full truncate max-w-[130px]">
                        {{ $post->user->school->school_name }}
                    </span>
                @endif

                <!-- Username -->
                <span class="text-xs text-slate-400 dark:text-zinc-500 font-medium truncate max-w-[100px]">
                    @<span>{{ $post->user->username }}</span>
                </span>

                <!-- Separator Dot -->
                <span class="text-slate-300 dark:text-zinc-600 text-xs shrink-0">•</span>

                <!-- Indonesian Relative Timestamp -->
                <span class="text-xs text-slate-400 dark:text-zinc-500 font-medium shrink-0">
                    {{ $post->created_at->diffForHumans() }}
                </span>
            </div>

            <!-- Actions (3 dots menu for author & other users) -->
            <div class="relative shrink-0" x-data="{ open: false }">
                <button type="button" @click="open = !open" @click.away="open = false" 
                    class="p-1 rounded-full text-slate-400 dark:text-zinc-500 hover:text-slate-700 dark:hover:text-zinc-200 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors"
                    title="Opsi Postingan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                    </svg>
                </button>
                <div x-show="open" x-cloak 
                    class="absolute right-0 mt-1 w-44 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-xl py-1 z-30">
                    <!-- Detail View Action -->
                    <button type="button" @click="showDetailModal = true; open = false" 
                        class="w-full text-left px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Baca Detail
                    </button>

                    @auth
                        @if($post->user_id === Auth::id())
                            <button type="button" @click="showEditModal = true; open = false" 
                                class="w-full text-left px-3.5 py-2 text-xs font-semibold text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Teks
                            </button>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus postingan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Postingan
                                </button>
                            </form>
                        @else
                            <button type="button" @click="showReportModal = true; open = false" 
                                class="w-full text-left px-3.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span x-text="hasReported ? 'Sudah Dilaporkan' : 'Laporkan Konten'"></span>
                            </button>
                        @endif
                    @else
                        <button type="button" @click="$dispatch('open-auth-modal', { message: 'Masuk ke Sirkelku dulu yuk buat melaporkan postingan ini!' }); open = false" 
                            class="w-full text-left px-3.5 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Laporkan Konten
                        </button>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Hobby & Community Badges -->
        @if($post->hobby || $post->community)
            <div class="flex items-center gap-1.5 mt-1 flex-wrap">
                @if($post->hobby)
                    <span class="inline-flex items-center text-[10px] font-bold text-[#FF4500] bg-[#FFF0E6] dark:bg-[#FF4500]/15 border border-[#F0D5C6] dark:border-[#FF4500]/30 px-2 py-0.5 rounded-full">
                        #{{ $post->hobby->name }}
                    </span>
                @endif
                @if($post->community)
                    <a href="{{ route('communities.show', $post->community->slug) }}" 
                       class="inline-flex items-center text-[10px] font-bold text-slate-700 dark:text-zinc-300 bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 px-2 py-0.5 rounded-full hover:bg-slate-200 dark:hover:bg-zinc-700 transition-colors">
                        Sirkel: {{ $post->community->name }}
                    </a>
                @endif
            </div>
        @endif

        <!-- Warning Overlay Banner when post is flagged -->
        <template x-if="isFlagged">
            <div class="mt-2.5 p-3 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 flex items-center justify-between gap-3 text-xs shadow-xs">
                <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-4 h-4 text-amber-700 dark:text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="font-medium">Konten disembunyikan karena dilaporkan oleh komunitas.</span>
                </div>
                <button type="button" @click="showFlaggedContent = !showFlaggedContent" 
                    class="px-3 py-1 rounded-xl bg-amber-200 dark:bg-amber-800 hover:bg-amber-300 dark:hover:bg-amber-700 text-amber-950 dark:text-amber-100 font-bold shrink-0 text-xs transition-colors cursor-pointer">
                    <span x-text="showFlaggedContent ? 'Sembunyikan' : 'Buka Sensor'"></span>
                </button>
            </div>
        </template>

        <!-- Body Content -->
        <div class="mt-2 text-sm text-slate-800 dark:text-zinc-100 leading-relaxed font-normal whitespace-pre-line break-words transition-all duration-300"
            :class="(isFlagged && !showFlaggedContent) ? 'filter blur-xs select-none opacity-60' : ''">
            {{ $post->content }}
        </div>

        <!-- Optional Media Attachment (Image / Video) -->
        @if($post->image_url)
            <div class="mt-3 relative rounded-2xl overflow-hidden border border-slate-200 dark:border-zinc-800 max-h-[480px] bg-slate-900 flex items-center justify-center">
                <!-- Media Display -->
                <div class="w-full h-full flex items-center justify-center transition-all duration-300"
                    :class="(isFlagged && !showFlaggedContent) ? 'filter blur-xl scale-105 pointer-events-none select-none' : ''">
                    @if(\Illuminate\Support\Str::endsWith(strtolower($post->image_path), ['.mp4', '.webm', '.mov']))
                        <video src="{{ $post->image_url }}" controls class="w-full aspect-video rounded-2xl"></video>
                    @else
                        <img src="{{ $post->image_url }}" alt="Kiriman {{ $post->user->name }}" class="w-full h-auto object-cover max-h-[480px] rounded-2xl cursor-pointer" @click="showDetailModal = true">
                    @endif
                </div>

                <!-- Overlay Warning on top of Media -->
                <template x-if="isFlagged && !showFlaggedContent">
                    <div class="absolute inset-0 z-10 bg-slate-950/65 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center text-white select-none">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-sm max-w-sm text-white">Konten disembunyikan karena dilaporkan oleh komunitas.</h4>
                        <p class="text-xs text-slate-300 mt-1.5 max-w-xs font-normal">Postingan menerima beberapa laporan dari pengguna.</p>
                        <button type="button" @click="showFlaggedContent = true"
                            class="mt-4 px-4 py-2 rounded-xl bg-white/20 hover:bg-white/30 text-white font-bold text-xs backdrop-blur-sm border border-white/30 transition-all shadow-sm hover:scale-105 cursor-pointer">
                            Buka Sensor
                        </button>
                    </div>
                </template>
            </div>
        @endif

        <!-- Action Footer (Interactions) -->
        <div class="flex items-center justify-between mt-3 text-xs text-slate-500 dark:text-zinc-400 font-semibold max-w-md pt-1">
            <!-- Komentar Button (Guest and Auth can open & read comments) -->
            <button type="button" @click="showComments = !showComments" 
                class="flex items-center gap-1.5 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors p-1.5 rounded-full hover:bg-orange-50 dark:hover:bg-zinc-800 group/btn" 
                title="Komentar">
                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500 group-hover/btn:text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span>{{ $post->comments_count }}</span>
            </button>

            <!-- Like Button (Auth Wall for Guest) -->
            <button type="button" @click="toggleLike()" 
                class="flex items-center gap-1.5 transition-colors p-1.5 rounded-full hover:bg-rose-50 dark:hover:bg-rose-950/30 group/btn"
                :class="isLiked ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500 dark:text-zinc-400 hover:text-rose-600'"
                title="Suka">
                <svg class="w-4 h-4" :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span x-text="likesCount"></span>
            </button>

            <!-- Bagikan (Copy Link) -->
            <button type="button" onclick="copyToClipboard('{{ route('feed.index') }}#post-{{ $post->id }}', 'Tautan postingan berhasil disalin!')" 
                class="flex items-center gap-1.5 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors p-1.5 rounded-full hover:bg-orange-50 dark:hover:bg-zinc-800 group/btn" 
                title="Bagikan">
                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500 group-hover/btn:text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                </svg>
            </button>

            <!-- Bookmark / Simpan (Auth Wall for Guest) -->
            <button type="button" @click="toggleBookmark()" 
                class="flex items-center gap-1.5 transition-colors p-1.5 rounded-full hover:bg-orange-50 dark:hover:bg-zinc-800 group/btn" 
                :class="isSaved ? 'text-[#FF4500]' : 'text-slate-500 dark:text-zinc-400 hover:text-[#FF4500]'"
                title="Bookmark">
                <svg class="w-4 h-4" :fill="isSaved ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
            </button>
        </div>

        <!-- Comments Collapsible Section (Guest can read, comment form restricted) -->
        <div x-show="showComments" x-cloak class="mt-3 pt-3 border-t border-slate-100 dark:border-zinc-800 space-y-3">
            @auth
                <!-- Comment Input Form for Authenticated Users -->
                <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="comment_text" id="comment-input-{{ $post->id }}" aria-label="Tulis komentar" required placeholder="Tulis komentar seru..." 
                        class="sk-input flex-1 py-1.5 text-xs rounded-full px-3.5 border border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-slate-800 dark:text-zinc-100">
                    <button type="submit" class="sk-btn-primary text-xs py-1.5 px-3.5 rounded-full shrink-0">
                        Kirim
                    </button>
                </form>
            @else
                <!-- Guest Comment Input (Auth Wall Prompt) -->
                <div class="flex gap-2 cursor-pointer group"
                     @click="$dispatch('open-auth-modal', { message: 'Masuk ke Sirkelku dulu yuk buat like, komentar, atau simpan postingan ini!' })">
                    <input type="text" readonly placeholder="Tulis komentar seru... (Masuk untuk berkomentar)" 
                        class="sk-input flex-1 py-1.5 text-xs rounded-full px-3.5 border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800/60 text-slate-400 dark:text-zinc-500 cursor-pointer">
                    <button type="button" class="sk-btn-primary text-xs py-1.5 px-3.5 rounded-full shrink-0">
                        Kirim
                    </button>
                </div>
            @endauth

            <!-- Comments List (Both Guest and Auth can read all comments) -->
            <div class="space-y-2 max-h-64 overflow-y-auto pr-1 no-scrollbar">
                @forelse($post->comments as $comment)
                    <div class="flex items-start gap-2.5 p-2.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/70 text-xs border border-slate-100 dark:border-zinc-700/60">
                        <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" 
                            class="w-6 h-6 rounded-full object-cover shrink-0 mt-0.5 ring-1 ring-slate-200 dark:ring-zinc-700">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-1">
                                <a href="{{ route('profile.show', $comment->user->username) }}" 
                                    class="font-bold text-slate-800 dark:text-zinc-100 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors truncate">
                                    {{ $comment->user->name }}
                                </a>
                                <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-medium shrink-0">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-700 dark:text-zinc-300 mt-0.5 leading-relaxed font-normal break-words">{{ $comment->comment_text }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-[11px] text-slate-400 dark:text-zinc-500 text-center py-2 font-medium">Belum ada komentar.</p>
                @endforelse
            </div>
        </div>

        <!-- Post Detail Modal (Reddit-Style Post Reader for Full Caption & Comments) -->
        <div x-show="showDetailModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/75 backdrop-blur-xs">
            <div @click.away="showDetailModal = false" class="sk-card bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 max-w-xl w-full max-h-[85vh] overflow-y-auto space-y-4 shadow-2xl rounded-3xl">
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-200 dark:ring-zinc-700">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-zinc-100">{{ $post->user->name }}</h3>
                            <p class="text-xs text-slate-400 dark:text-zinc-500">@<span>{{ $post->user->username }}</span> • {{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <button type="button" @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-200 text-xl font-bold">&times;</button>
                </div>

                <!-- Full Caption Body -->
                <div class="text-sm text-slate-800 dark:text-zinc-100 leading-relaxed font-normal whitespace-pre-line break-words">
                    {{ $post->content }}
                </div>

                <!-- Full Image (if exists) -->
                @if($post->image_url)
                    <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-zinc-800 bg-slate-950">
                        <img src="{{ $post->image_url }}" alt="{{ $post->user->name }}" class="w-full max-h-96 object-contain mx-auto">
                    </div>
                @endif

                <!-- Comments in Modal -->
                <div class="pt-3 border-t border-slate-100 dark:border-zinc-800 space-y-3">
                    <h4 class="text-xs font-black text-slate-900 dark:text-zinc-100">Komentar ({{ $post->comments_count }})</h4>
                    <div class="space-y-2 max-h-48 overflow-y-auto pr-1 no-scrollbar">
                        @forelse($post->comments as $comment)
                            <div class="p-2.5 rounded-2xl bg-slate-50 dark:bg-zinc-800/70 text-xs border border-slate-100 dark:border-zinc-700/60">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="font-bold text-slate-800 dark:text-zinc-100">{{ $comment->user->name }}</span>
                                    <span class="text-[10px] text-slate-400 dark:text-zinc-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-slate-700 dark:text-zinc-300">{{ $comment->comment_text }}</p>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        @auth
            <!-- Edit Post Modal -->
            <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 dark:bg-black/70 backdrop-blur-xs">
                <div @click.away="showEditModal = false" class="sk-card bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 max-w-lg w-full space-y-4 shadow-xl rounded-3xl">
                    <h3 class="font-black text-sm text-slate-800 dark:text-zinc-100">Edit Postingan</h3>
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" class="space-y-3.5">
                        @csrf
                        @method('PUT')
                        <textarea id="edit-post-content-{{ $post->id }}" name="content" aria-label="Konten Postingan" rows="4" required class="sk-input text-sm p-3 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-100">{{ $post->content }}</textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showEditModal = false" class="sk-btn-outline text-xs py-2 px-3">Batal</button>
                            <button type="submit" class="sk-btn-primary text-xs py-2 px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Post Modal -->
            <div x-show="showReportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 dark:bg-black/75 backdrop-blur-xs">
                <div @click.away="showReportModal = false" class="sk-card bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 max-w-md w-full space-y-4 shadow-2xl rounded-3xl">
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-zinc-800 pb-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-950/40 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-black text-sm text-slate-800 dark:text-zinc-100">Laporkan Konten</h3>
                                <p class="text-[11px] text-slate-400 dark:text-zinc-500">Bantu jaga kenyamanan sirkel</p>
                            </div>
                        </div>
                        <button type="button" @click="showReportModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-zinc-300 text-lg leading-none cursor-pointer">&times;</button>
                    </div>

                    <form @submit.prevent="submitReport()" class="space-y-3.5">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-2">Pilih Alasan Pelaporan:</label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-2.5 p-2 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer text-xs font-medium text-slate-700 dark:text-zinc-300 transition-colors">
                                    <input type="radio" name="reason" value="Kata-kata kasar atau penghinaan" x-model="reportReason" class="text-[#FF4500] focus:ring-[#FF4500]">
                                    <span>Kata-kata kasar atau penghinaan</span>
                                </label>
                                <label class="flex items-center gap-2.5 p-2 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer text-xs font-medium text-slate-700 dark:text-zinc-300 transition-colors">
                                    <input type="radio" name="reason" value="Konten tidak pantas / SARA / Dewasa" x-model="reportReason" class="text-[#FF4500] focus:ring-[#FF4500]">
                                    <span>Konten tidak pantas / SARA / Dewasa</span>
                                </label>
                                <label class="flex items-center gap-2.5 p-2 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer text-xs font-medium text-slate-700 dark:text-zinc-300 transition-colors">
                                    <input type="radio" name="reason" value="Spam atau penipuan" x-model="reportReason" class="text-[#FF4500] focus:ring-[#FF4500]">
                                    <span>Spam atau penipuan</span>
                                </label>
                                <label class="flex items-center gap-2.5 p-2 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer text-xs font-medium text-slate-700 dark:text-zinc-300 transition-colors">
                                    <input type="radio" name="reason" value="Ujaran kebencian atau pelecehan" x-model="reportReason" class="text-[#FF4500] focus:ring-[#FF4500]">
                                    <span>Ujaran kebencian atau pelecehan</span>
                                </label>
                                <label class="flex items-center gap-2.5 p-2 rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-800 cursor-pointer text-xs font-medium text-slate-700 dark:text-zinc-300 transition-colors">
                                    <input type="radio" name="reason" value="Lainnya" x-model="reportReason" class="text-[#FF4500] focus:ring-[#FF4500]">
                                    <span>Alasan lainnya</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="reportReason === 'Lainnya'" x-cloak>
                            <label for="report-custom-reason-{{ $post->id }}" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">Rincian Tambahan (Opsional):</label>
                            <textarea id="report-custom-reason-{{ $post->id }}" name="custom_reason" aria-label="Rincian Tambahan Alasan Laporan" x-model="reportCustomReason" rows="2" placeholder="Tuliskan detail alasan laporan..." class="sk-input text-xs p-2.5 w-full rounded-xl border border-slate-200 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2 border-t border-slate-100 dark:border-zinc-800">
                            <button type="button" @click="showReportModal = false" class="sk-btn-outline text-xs py-2 px-3">Batal</button>
                            <button type="submit" :disabled="!reportReason || isReporting" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-colors disabled:opacity-50 cursor-pointer">
                                <span x-text="isReporting ? 'Mengirim...' : 'Kirim Laporan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth

    </div>
</article>
