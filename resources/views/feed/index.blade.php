@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Create Post Card -->
    <div class="sk-card p-4 sm:p-5" x-data="{ hasImage: false, imagePreview: null }">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3.5">
            @csrf

            <div class="flex items-start gap-3">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-orange-200 shrink-0 mt-0.5">
                <div class="flex-1">
                    <textarea name="content" rows="2.5" required placeholder="Bagikan cerita, karya, atau kabar seru sekolah hari ini..." 
                        class="w-full bg-orange-50/40 hover:bg-orange-50/70 focus:bg-white text-sm text-slate-800 placeholder:text-slate-400 p-3 rounded-xl border border-orange-100 focus:border-orange-400 focus:ring-2 focus:ring-orange-100 focus:outline-none transition-all resize-none leading-relaxed font-medium">{{ old('content') }}</textarea>
                </div>
            </div>

            <!-- Image Preview Box -->
            <div x-show="hasImage" x-cloak class="relative rounded-xl overflow-hidden border border-orange-200 max-h-60 bg-orange-50/50">
                <img :src="imagePreview" class="w-full h-full object-cover">
                <button type="button" @click="hasImage = false; imagePreview = null; document.getElementById('post-image-input').value = ''" 
                    class="absolute top-2 right-2 w-7 h-7 rounded-full bg-slate-900/80 text-white flex items-center justify-center hover:bg-slate-900 transition-colors text-sm font-bold">
                    &times;
                </button>
            </div>

            <!-- Options Bar -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-orange-100">
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Image Upload Trigger -->
                    <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-orange-200/80 bg-orange-50/50 hover:bg-orange-100/70 text-orange-800 text-xs font-bold cursor-pointer transition-colors">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span>Foto / Karya</span>
                        <input type="file" id="post-image-input" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only"
                            @change="const file = $event.target.files[0]; if (file) { hasImage = true; imagePreview = URL.createObjectURL(file); }">
                    </label>

                    <!-- Hobby Selector -->
                    <select name="hobby_id" class="px-3 py-1.5 rounded-xl border border-orange-200/80 bg-orange-50/50 hover:bg-orange-100/70 text-slate-700 text-xs font-semibold focus:outline-none focus:border-orange-400 cursor-pointer">
                        <option value="">Tag Hobi (Opsional)</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->id }}" {{ old('hobby_id') == $hobby->id ? 'selected' : '' }}>#{{ $hobby->name }}</option>
                        @endforeach
                    </select>

                    <!-- Circle / Community Selector -->
                    @if($userCommunities->isNotEmpty())
                        <select name="community_id" class="px-3 py-1.5 rounded-xl border border-orange-200/80 bg-orange-50/50 hover:bg-orange-100/70 text-slate-700 text-xs font-semibold focus:outline-none focus:border-orange-400 cursor-pointer">
                            <option value="">Publik (Semua Pelajar)</option>
                            @foreach($userCommunities as $comm)
                                <option value="{{ $comm->id }}" {{ old('community_id') == $comm->id ? 'selected' : '' }}>Sirkel: {{ $comm->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <button type="submit" class="sk-btn-primary text-xs py-2 px-4">
                    Kirim Postingan
                </button>
            </div>
        </form>
    </div>

    <!-- Feed Filter Header & Tabs -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white p-2.5 rounded-2xl border border-orange-100/80 shadow-xs">
        <div class="flex items-center gap-1.5">
            <a href="{{ route('feed.index') }}" 
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ !request('tab') ? 'bg-orange-500 text-white shadow-xs' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}">
                Linimasa Utama
            </a>
            <a href="{{ route('feed.index', ['tab' => 'my_circles']) }}" 
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ request('tab') === 'my_circles' ? 'bg-orange-500 text-white shadow-xs' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}">
                Sirkel Saya
            </a>
        </div>

        <!-- Filter by Hobby Dropdown -->
        <div class="w-full sm:w-auto">
            <form action="{{ route('feed.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('tab')) <input type="hidden" name="tab" value="{{ request('tab') }}"> @endif
                <select name="hobby" onchange="this.form.submit()" class="w-full sm:w-auto px-3 py-1.5 text-xs font-semibold rounded-xl bg-orange-50/50 text-slate-700 border border-orange-200/80 focus:outline-none focus:border-orange-400">
                    <option value="">Semua Kategori Hobi</option>
                    @foreach($hobbies as $h)
                        <option value="{{ $h->id }}" {{ request('hobby') == $h->id ? 'selected' : '' }}>#{{ $h->name }}</option>
                    @endforeach
                </select>
                @if(request('hobby'))
                    <a href="{{ route('feed.index', ['tab' => request('tab')]) }}" class="text-xs text-rose-600 font-bold hover:underline">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Feed Posts List -->
    <div class="space-y-4">
        @forelse($posts as $post)
            <div id="post-{{ $post->id }}" class="sk-card p-5 space-y-3.5"
                x-data="{ 
                    isLiked: {{ $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}, 
                    likesCount: {{ $post->likes_count }},
                    showComments: false,
                    showEditModal: false,
                    toggleLike() {
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
                    }
                }">
                
                <!-- Post Header -->
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.show', $post->user->username) }}" class="group flex items-center gap-3">
                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-xl object-cover ring-2 ring-orange-200 group-hover:ring-orange-400 transition-all">
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <h4 class="text-sm font-bold text-slate-900 group-hover:text-orange-600 transition-colors">{{ $post->user->name }}</h4>
                                    <span class="text-xs text-orange-600 font-semibold">@<span>{{ $post->user->username }}</span></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-400 font-medium">
                                    <span>{{ $post->user->school ? $post->user->school->school_name : 'Pelajar' }}</span>
                                    <span>•</span>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Post Badges & Actions Menu -->
                    <div class="flex items-center gap-2">
                        @if($post->hobby)
                            <span class="sk-badge-orange">
                                #{{ $post->hobby->name }}
                            </span>
                        @endif

                        @if($post->community)
                            <a href="{{ route('communities.show', $post->community->slug) }}" class="hidden sm:inline-block sk-badge-teal hover:opacity-80 transition-opacity">
                                Sirkel: {{ $post->community->name }}
                            </a>
                        @endif

                        <!-- Author Actions Dropdown -->
                        @if($post->user_id === Auth::id())
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="p-1.5 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-orange-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                </button>
                                <div x-show="open" x-cloak class="absolute right-0 mt-1 w-36 bg-white border border-orange-100 rounded-xl shadow-md py-1 z-20">
                                    <button @click="showEditModal = true; open = false" class="w-full text-left px-3.5 py-2 text-xs font-semibold text-slate-700 hover:bg-orange-50 flex items-center gap-2">
                                        Edit Teks
                                    </button>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus postingan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Post Content -->
                <div class="text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium">
                    {{ $post->content }}
                </div>

                <!-- Post Image Attachment -->
                @if($post->image_url)
                    <div class="rounded-xl overflow-hidden border border-orange-100 bg-slate-900 max-h-[480px] flex items-center justify-center">
                        <img src="{{ $post->image_url }}" alt="Kiriman {{ $post->user->name }}" class="w-full h-auto object-cover max-h-[480px]">
                    </div>
                @endif

                <!-- Post Interactivity Bar -->
                <div class="flex items-center justify-between pt-3 border-t border-orange-100 text-xs font-semibold text-slate-600">
                    <div class="flex items-center gap-3">
                        <!-- Like Button (AJAX) -->
                        <button @click="toggleLike()" class="flex items-center gap-1.5 py-1.5 px-3 rounded-xl transition-all font-bold"
                            :class="isLiked ? 'text-rose-600 bg-rose-50 border border-rose-200' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600'">
                            <svg class="w-4 h-4" :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span x-text="likesCount"></span>
                        </button>

                        <!-- Comments Toggle Button -->
                        <button @click="showComments = !showComments" class="flex items-center gap-1.5 py-1.5 px-3 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition-all text-slate-600 font-bold">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ $post->comments_count }} Komentar</span>
                        </button>
                    </div>

                    <!-- Share Button (Copy Link) -->
                    <button type="button" onclick="copyToClipboard('{{ url()->current() }}#post-{{ $post->id }}', 'Tautan postingan berhasil disalin!')" 
                        class="flex items-center gap-1.5 py-1.5 px-3 rounded-xl text-slate-500 hover:text-orange-600 hover:bg-orange-50 transition-all font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <span>Bagikan</span>
                    </button>
                </div>

                <!-- Comments Section -->
                <div x-show="showComments" x-cloak class="pt-3.5 border-t border-orange-100 space-y-3">
                    <!-- Comment Input Form -->
                    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="comment_text" required placeholder="Tulis komentar seru..." 
                            class="sk-input flex-1 py-2 text-xs">
                        <button type="submit" class="sk-btn-primary text-xs py-2 px-3.5 shrink-0">
                            Kirim
                        </button>
                    </form>

                    <!-- Comments List -->
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1 custom-scrollbar">
                        @forelse($post->comments as $comment)
                            <div class="flex items-start gap-2.5 p-2.5 rounded-xl bg-orange-50/40 text-xs border border-orange-100/80">
                                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-6 h-6 rounded-full object-cover shrink-0 mt-0.5">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('profile.show', $comment->user->username) }}" class="font-bold text-slate-900 hover:text-orange-600 transition-colors">
                                            {{ $comment->user->name }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-slate-700 mt-0.5 leading-relaxed font-medium">{{ $comment->comment_text }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-[11px] text-slate-400 text-center py-2 font-medium">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Edit Modal -->
                <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs">
                    <div @click.away="showEditModal = false" class="sk-card p-6 max-w-lg w-full space-y-4 shadow-lg">
                        <h3 class="font-extrabold text-sm text-slate-900">Edit Postingan</h3>
                        <form action="{{ route('posts.update', $post->id) }}" method="POST" class="space-y-3.5">
                            @csrf
                            @method('PUT')
                            <textarea name="content" rows="4" required class="sk-input text-sm p-3">{{ $post->content }}</textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="showEditModal = false" class="sk-btn-outline text-xs py-2 px-3">Batal</button>
                                <button type="submit" class="sk-btn-primary text-xs py-2 px-4">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="sk-card p-10 text-center space-y-2">
                <h3 class="font-bold text-sm text-slate-800">Belum Ada Postingan</h3>
                <p class="text-xs text-slate-500 max-w-xs mx-auto font-medium">Jadilah yang pertama berbagi cerita atau postingan hobi di linimasa Nongkrong Yuk!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $posts->links() }}
    </div>

</div>
@endsection
