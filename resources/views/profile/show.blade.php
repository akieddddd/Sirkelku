@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Profile Header Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl object-cover ring-4 ring-indigo-50 shadow-md">
                <div class="space-y-1">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $user->name }}</h1>
                    <p class="text-xs font-bold text-indigo-600">@<span>{{ $user->username }}</span></p>
                    <p class="text-xs text-slate-500 flex items-center gap-1.5 pt-0.5">
                        <span>🏫</span>
                        <span>{{ $user->school ? $user->school->school_name : 'Belum memilih sekolah' }}</span>
                        @if($user->school)
                            <span class="text-slate-400">({{ $user->school->city }})</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Profile Action Button -->
            <div>
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-all">
                        ⚙️ Edit Profil & Hobi
                    </a>
                @else
                    <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $user->username]) }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-md shadow-indigo-600/30 transition-all">
                        🤝 Ajak Main
                    </a>
                @endif
            </div>
        </div>

        <!-- Bio -->
        @if($user->bio)
            <div class="bg-slate-50 p-4 rounded-2xl text-xs sm:text-sm text-slate-700 leading-relaxed">
                "{{ $user->bio }}"
            </div>
        @endif

        <!-- Hobbies Pills -->
        <div class="space-y-2">
            <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Hobi & Minat Utama</h3>
            <div class="flex flex-wrap gap-2">
                @forelse($user->hobbies as $hobby)
                    <span class="px-3 py-1.5 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-extrabold">
                        #{{ $hobby->name }}
                    </span>
                @empty
                    <span class="text-xs text-slate-400">Belum memilih hobi.</span>
                @endforelse
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex items-center gap-2 pt-4 border-t border-slate-100">
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'posts']) }}"
                class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ (!request('tab') || request('tab') === 'posts') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                📸 Kiriman ({{ $user->posts->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'circles']) }}"
                class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ request('tab') === 'circles' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                👥 Sirkel ({{ $user->communities->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'threads']) }}"
                class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ request('tab') === 'threads' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                💬 Utas Forum ({{ $user->threads->count() }})
            </a>
        </div>
    </div>

    <!-- TAB 1: POSTS -->
    @if(!request('tab') || request('tab') === 'posts')
        <div class="space-y-4">
            @forelse($posts as $post)
                <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-200/80 space-y-3">
                    <p class="text-xs text-slate-400">{{ $post->created_at->diffForHumans() }}</p>
                    <p class="text-sm text-slate-800 leading-relaxed">{{ $post->content }}</p>
                    @if($post->image_url)
                        <div class="rounded-2xl overflow-hidden max-h-96">
                            <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                        </div>
                    @endif
                    <div class="pt-2 border-t border-slate-100 flex items-center gap-4 text-xs font-bold text-slate-500">
                        <span>❤️ {{ $post->likes_count }} Suka</span>
                        <span>💬 {{ $post->comments_count }} Komentar</span>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-10 text-center text-slate-400 text-xs border border-slate-200/80">
                    Pengguna ini belum membagikan postingan di Nongkrong Yuk.
                </div>
            @endforelse
            <div>{{ $posts->links() }}</div>
        </div>

    <!-- TAB 2: CIRCLES -->
    @elseif(request('tab') === 'circles')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($communities as $comm)
                <a href="{{ route('communities.show', $comm->slug) }}" class="bg-white p-4 rounded-3xl border border-slate-200/80 hover:border-slate-300 shadow-sm flex items-center gap-3 group transition-all">
                    <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-12 h-12 rounded-2xl object-cover ring-1 ring-slate-200">
                    <div class="min-w-0">
                        <h4 class="text-sm font-bold text-slate-900 group-hover:text-indigo-600 truncate">{{ $comm->name }}</h4>
                        <p class="text-xs text-slate-400 truncate">{{ $comm->members_count }} Anggota</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-10 text-center text-slate-400 text-xs border border-slate-200/80">
                    Belum bergabung dengan sirkel mana pun.
                </div>
            @endforelse
            <div class="col-span-full">{{ $communities->links() }}</div>
        </div>

    <!-- TAB 3: THREADS -->
    @elseif(request('tab') === 'threads')
        <div class="space-y-3">
            @forelse($threads as $thread)
                <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 rounded-3xl bg-white border border-slate-200/80 hover:border-slate-300 shadow-sm group transition-all">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-bold text-indigo-600">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                        <span class="text-slate-400">{{ $thread->comments_count }} Balasan</span>
                    </div>
                    <h4 class="font-bold text-sm text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $thread->title }}</h4>
                </a>
            @empty
                <div class="bg-white rounded-3xl p-10 text-center text-slate-400 text-xs border border-slate-200/80">
                    Belum membuat utas di Tongkrongan.id.
                </div>
            @endforelse
            <div>{{ $threads->links() }}</div>
        </div>
    @endif

</div>
@endsection
