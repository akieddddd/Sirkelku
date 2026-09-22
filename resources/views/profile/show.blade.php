@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Profile Header Card -->
    <div class="sk-card space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover ring-4 ring-orange-200 shadow-sm">
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <h1 class="text-lg sm:text-xl font-black text-slate-900">{{ $user->name }}</h1>
                        <span class="sk-badge-orange text-[10px]">Pelajar</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">@<span>{{ $user->username }}</span></p>
                    <p class="text-xs text-slate-600 flex items-center gap-1.5 pt-1 font-medium">
                        <span>🏫 {{ $user->school ? $user->school->school_name : 'Belum memilih sekolah' }}</span>
                        @if($user->school)
                            <span class="text-slate-400">({{ $user->school->city }})</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Profile Action Button -->
            <div>
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}" class="sk-btn-outline text-xs px-4 py-2">
                        ⚙️ Edit Profil & Hobi
                    </a>
                @else
                    <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $user->username]) }}" class="sk-btn-primary text-xs px-4 py-2">
                        🤝 Ajak Main
                    </a>
                @endif
            </div>
        </div>

        <!-- Bio -->
        @if($user->bio)
            <div class="bg-amber-50/50 p-3.5 rounded-xl border border-amber-200/80 text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                "{{ $user->bio }}"
            </div>
        @endif

        <!-- Hobbies Pills -->
        <div class="space-y-1.5 pt-1">
            <h3 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hobi & Minat Utama</h3>
            <div class="flex flex-wrap gap-1.5">
                @forelse($user->hobbies as $hobby)
                    <span class="sk-pill text-xs">
                        #{{ $hobby->name }}
                    </span>
                @empty
                    <span class="text-xs text-slate-400 italic">Belum memilih hobi.</span>
                @endforelse
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex items-center gap-1.5 pt-4 border-t border-amber-100 overflow-x-auto">
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'posts']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ (!request('tab') || request('tab') === 'posts') ? 'bg-orange-500 text-white shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}">
                📝 Kiriman ({{ $user->posts->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'circles']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('tab') === 'circles' ? 'bg-orange-500 text-white shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}">
                ⭕ Sirkel ({{ $user->communities->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'threads']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('tab') === 'threads' ? 'bg-orange-500 text-white shadow-sm' : 'text-slate-600 hover:bg-orange-50 hover:text-orange-600' }}">
                💬 Utas Forum ({{ $user->threads->count() }})
            </a>
        </div>
    </div>

    <!-- TAB 1: POSTS -->
    @if(!request('tab') || request('tab') === 'posts')
        <div class="space-y-3">
            @forelse($posts as $post)
                <div class="sk-card space-y-2.5">
                    <p class="text-xs text-slate-400 font-medium">🕒 {{ $post->created_at->diffForHumans() }}</p>
                    <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">{{ $post->content }}</p>
                    @if($post->image_url)
                        <div class="rounded-xl overflow-hidden max-h-96 border border-amber-200 bg-slate-900">
                            <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                        </div>
                    @endif
                    <div class="pt-3 border-t border-amber-100 flex items-center gap-4 text-xs font-semibold text-slate-500">
                        <span class="hover:text-orange-600 transition-colors">❤️ {{ $post->likes_count }} Suka</span>
                        <span class="hover:text-orange-600 transition-colors">💬 {{ $post->comments_count }} Komentar</span>
                    </div>
                </div>
            @empty
                <div class="sk-card p-8 text-center text-slate-400 text-xs font-medium">
                    Pengguna ini belum membagikan postingan di Nongkrong Yuk.
                </div>
            @endforelse
            <div>{{ $posts->links() }}</div>
        </div>

    <!-- TAB 2: CIRCLES -->
    @elseif(request('tab') === 'circles')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($communities as $comm)
                <a href="{{ route('communities.show', $comm->slug) }}" class="sk-card-compact flex items-center gap-3 group hover:border-orange-300 hover:shadow-md transition-all">
                    <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-11 h-11 rounded-xl object-cover ring-2 ring-orange-100 group-hover:ring-orange-300">
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-orange-600 truncate transition-colors">{{ $comm->name }}</h4>
                        <p class="text-xs text-slate-400 font-medium truncate">👥 {{ $comm->members_count }} Anggota</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full sk-card p-8 text-center text-slate-400 text-xs font-medium">
                    Belum bergabung dengan sirkel mana pun.
                </div>
            @endforelse
            <div class="col-span-full">{{ $communities->links() }}</div>
        </div>

    <!-- TAB 3: THREADS -->
    @elseif(request('tab') === 'threads')
        <div class="sk-card p-0 divide-y divide-amber-100 overflow-hidden">
            @forelse($threads as $thread)
                <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 hover:bg-orange-50/50 transition-colors group">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="sk-badge-orange text-[10px]">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                        <span class="text-slate-400 font-medium">💬 {{ $thread->comments_count }} Balasan</span>
                    </div>
                    <h4 class="font-bold text-sm text-slate-900 group-hover:text-orange-600 transition-colors">{{ $thread->title }}</h4>
                </a>
            @empty
                <div class="p-8 text-center text-slate-400 text-xs font-medium">
                    Belum membuat utas di Tongkrongan.id.
                </div>
            @endforelse
        </div>
        <div class="pt-2">{{ $threads->links() }}</div>
    @endif

</div>
@endsection

