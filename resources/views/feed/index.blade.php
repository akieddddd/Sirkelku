@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Create Post Card (Twitter / LinkedIn style minimalist) -->
    <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-zinc-200" x-data="{ hasImage: false, imagePreview: null }">
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf

            <div class="flex items-start gap-3">
                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-full object-cover ring-1 ring-zinc-200 shrink-0 mt-0.5">
                <div class="flex-1">
                    <textarea name="content" rows="2" required placeholder="Bagikan cerita, karya, atau kabar seru hari ini..." 
                        class="w-full bg-transparent text-sm text-zinc-800 placeholder:text-zinc-400 p-1 border-0 focus:ring-0 focus:outline-none transition-all resize-none leading-relaxed">{{ old('content') }}</textarea>
                </div>
            </div>

            <!-- Image Preview Box -->
            <div x-show="hasImage" x-cloak class="relative rounded-lg overflow-hidden border border-zinc-200 max-h-60 bg-zinc-50">
                <img :src="imagePreview" class="w-full h-full object-cover">
                <button type="button" @click="hasImage = false; imagePreview = null; document.getElementById('post-image-input').value = ''" 
                    class="absolute top-2 right-2 w-7 h-7 rounded-full bg-zinc-900/80 text-white flex items-center justify-center hover:bg-zinc-900 transition-colors text-sm">
                    &times;
                </button>
            </div>

            <!-- Options Bar -->
            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-zinc-100">
                <div class="flex flex-wrap items-center gap-2">
                    <!-- Image Upload Trigger -->
                    <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 text-xs font-medium cursor-pointer transition-colors">
                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span>Foto / Karya</span>
                        <input type="file" id="post-image-input" name="image" accept="image/jpeg,image/png,image/webp" class="sr-only"
                            @change="const file = $event.target.files[0]; if (file) { hasImage = true; imagePreview = URL.createObjectURL(file); }">
                    </label>

                    <!-- Hobby Selector -->
                    <select name="hobby_id" class="px-2.5 py-1.5 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 text-xs font-medium focus:outline-none focus:border-zinc-400 cursor-pointer">
                        <option value="">Tag Hobi (Opsional)</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->id }}" {{ old('hobby_id') == $hobby->id ? 'selected' : '' }}>#{{ $hobby->name }}</option>
                        @endforeach
                    </select>

                    <!-- Circle / Community Selector -->
                    @if($userCommunities->isNotEmpty())
                        <select name="community_id" class="px-2.5 py-1.5 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 text-xs font-medium focus:outline-none focus:border-zinc-400 cursor-pointer">
                            <option value="">Publik (Semua Pelajar)</option>
                            @foreach($userCommunities as $comm)
                                <option value="{{ $comm->id }}" {{ old('community_id') == $comm->id ? 'selected' : '' }}>Sirkel: {{ $comm->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <button type="submit" class="px-4 py-1.5 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-all shadow-sm">
                    Kirim Postingan
                </button>
            </div>
        </form>
    </div>

    <!-- Feed Filter Header & Tabs -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white p-2.5 rounded-xl border border-zinc-200 shadow-sm">
        <div class="flex items-center gap-1.5">
            <a href="{{ route('feed.index') }}" 
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ !request('tab') ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Linimasa Utama
            </a>
            <a href="{{ route('feed.index', ['tab' => 'my_circles']) }}" 
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('tab') === 'my_circles' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Sirkel Saya
            </a>
        </div>

        <!-- Filter by Hobby Dropdown -->
        <div class="w-full sm:w-auto">
            <form action="{{ route('feed.index') }}" method="GET" class="flex items-center gap-2">
                @if(request('tab')) <input type="hidden" name="tab" value="{{ request('tab') }}"> @endif
                <select name="hobby" onchange="this.form.submit()" class="w-full sm:w-auto px-3 py-1.5 text-xs font-medium rounded-lg bg-zinc-50 text-zinc-700 border border-zinc-200 focus:outline-none focus:border-zinc-400">
                    <option value="">Semua Kategori Hobi</option>
                    @foreach($hobbies as $h)
                        <option value="{{ $h->id }}" {{ request('hobby') == $h->id ? 'selected' : '' }}>#{{ $h->name }}</option>
                    @endforeach
                </select>
                @if(request('hobby'))
                    <a href="{{ route('feed.index', ['tab' => request('tab')]) }}" class="text-xs text-rose-600 hover:underline">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Feed Posts List -->
    <div class="space-y-4">
        @forelse($posts as $post)
            <div id="post-{{ $post->id }}" class="bg-white rounded-xl p-5 shadow-sm border border-zinc-200 space-y-3.5 transition-all hover:border-zinc-300"
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
                            <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-zinc-200 group-hover:ring-zinc-400 transition-all">
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <h4 class="text-sm font-semibold text-zinc-950 group-hover:text-blue-600 transition-colors">{{ $post->user->name }}</h4>
                                    <span class="text-xs text-zinc-400">@<span>{{ $post->user->username }}</span></span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-zinc-500">
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
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-700 border border-zinc-200">
                                #{{ $post->hobby->name }}
                            </span>
                        @endif

                        @if($post->community)
                            <a href="{{ route('communities.show', $post->community->slug) }}" class="hidden sm:inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-700 border border-zinc-200 hover:bg-zinc-200 transition-colors">
                                {{ $post->community->name }}
                            </a>
                        @endif

                        <!-- Author Actions Dropdown -->
                        @if($post->user_id === Auth::id())
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="p-1.5 text-zinc-400 hover:text-zinc-700 rounded-lg hover:bg-zinc-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                </button>
                                <div x-show="open" x-cloak class="absolute right-0 mt-1 w-36 bg-white border border-zinc-200 rounded-lg shadow-sm py-1 z-20">
                                    <button @click="showEditModal = true; open = false" class="w-full text-left px-3.5 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-50 flex items-center gap-2">
                                        Edit Teks
                                    </button>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus postingan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-3.5 py-1.5 text-xs font-medium text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Post Content -->
                <div class="text-sm text-zinc-800 leading-relaxed whitespace-pre-line font-normal">
                    {{ $post->content }}
                </div>

                <!-- Post Image Attachment -->
                @if($post->image_url)
                    <div class="rounded-lg overflow-hidden border border-zinc-200 bg-zinc-900 max-h-[480px] flex items-center justify-center">
                        <img src="{{ $post->image_url }}" alt="Kiriman {{ $post->user->name }}" class="w-full h-auto object-cover max-h-[480px]">
                    </div>
                @endif

                <!-- Post Interactivity Bar -->
                <div class="flex items-center justify-between pt-3 border-t border-zinc-100 text-xs font-medium text-zinc-600">
                    <div class="flex items-center gap-3">
                        <!-- Like Button (AJAX) -->
                        <button @click="toggleLike()" class="flex items-center gap-1.5 py-1 px-2.5 rounded-lg transition-colors"
                            :class="isLiked ? 'text-rose-600 bg-rose-50' : 'text-zinc-600 hover:bg-zinc-100'">
                            <svg class="w-4 h-4" :fill="isLiked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span x-text="likesCount"></span>
                        </button>

                        <!-- Comments Toggle Button -->
                        <button @click="showComments = !showComments" class="flex items-center gap-1.5 py-1 px-2.5 rounded-lg hover:bg-zinc-100 transition-colors text-zinc-600">
                            <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span>{{ $post->comments_count }} Komentar</span>
                        </button>
                    </div>

                    <!-- Share Button (Copy Link) -->
                    <button type="button" onclick="copyToClipboard('{{ url()->current() }}#post-{{ $post->id }}', 'Tautan postingan berhasil disalin!')" 
                        class="flex items-center gap-1.5 py-1 px-2.5 rounded-lg text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        <span>Bagikan</span>
                    </button>
                </div>

                <!-- Comments Section -->
                <div x-show="showComments" x-cloak class="pt-3 border-t border-zinc-100 space-y-3">
                    <!-- Comment Input Form -->
                    <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="text" name="comment_text" required placeholder="Tulis komentar..." 
                            class="flex-1 bg-zinc-50 focus:bg-white text-xs text-zinc-800 px-3 py-2 rounded-lg border border-zinc-200 focus:border-zinc-400 focus:outline-none">
                        <button type="submit" class="px-3.5 py-2 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-semibold transition-all shrink-0">
                            Kirim
                        </button>
                    </form>

                    <!-- Comments List -->
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @forelse($post->comments as $comment)
                            <div class="flex items-start gap-2.5 p-2.5 rounded-lg bg-zinc-50 text-xs border border-zinc-100">
                                <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-6 h-6 rounded-full object-cover shrink-0 mt-0.5">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <a href="{{ route('profile.show', $comment->user->username) }}" class="font-semibold text-zinc-900 hover:underline">
                                            {{ $comment->user->name }}
                                        </a>
                                        <span class="text-[10px] text-zinc-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-zinc-700 mt-0.5 leading-relaxed">{{ $comment->comment_text }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-[11px] text-zinc-400 text-center py-2">Belum ada komentar.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Edit Modal -->
                <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/50 backdrop-blur-xs">
                    <div @click.away="showEditModal = false" class="bg-white rounded-xl p-5 max-w-lg w-full shadow-lg border border-zinc-200 space-y-4">
                        <h3 class="font-bold text-sm text-zinc-950">Edit Postingan</h3>
                        <form action="{{ route('posts.update', $post->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <textarea name="content" rows="4" required class="w-full p-3 bg-zinc-50 text-sm rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400">{{ $post->content }}</textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="showEditModal = false" class="px-3 py-1.5 text-xs font-semibold text-zinc-600 hover:bg-zinc-100 rounded-lg">Batal</button>
                                <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold text-white bg-zinc-900 hover:bg-zinc-800 rounded-lg">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-xl p-10 text-center border border-zinc-200 shadow-sm space-y-2">
                <h3 class="font-bold text-sm text-zinc-800">Belum Ada Postingan</h3>
                <p class="text-xs text-zinc-500 max-w-xs mx-auto">Jadilah yang pertama berbagi cerita atau postingan hobi di linimasa Nongkrong Yuk!</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $posts->links() }}
    </div>

</div>
@endsection
