@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Profile Header Card -->
    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm border border-zinc-200 space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover ring-2 ring-zinc-200 shadow-xs">
                <div class="space-y-0.5">
                    <h1 class="text-lg sm:text-xl font-bold text-zinc-950">{{ $user->name }}</h1>
                    <p class="text-xs text-zinc-500">@<span>{{ $user->username }}</span></p>
                    <p class="text-xs text-zinc-600 flex items-center gap-1.5 pt-0.5">
                        <span>{{ $user->school ? $user->school->school_name : 'Belum memilih sekolah' }}</span>
                        @if($user->school)
                            <span class="text-zinc-400">({{ $user->school->city }})</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Profile Action Button -->
            <div>
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-zinc-200 bg-zinc-50 hover:bg-zinc-100 text-zinc-700 font-semibold text-xs transition-colors">
                        Edit Profil & Hobi
                    </a>
                @else
                    <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $user->username]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shadow-sm">
                        Ajak Main
                    </a>
                @endif
            </div>
        </div>

        <!-- Bio -->
        @if($user->bio)
            <div class="bg-zinc-50 p-3 rounded-lg border border-zinc-200 text-xs sm:text-sm text-zinc-700 leading-relaxed">
                "{{ $user->bio }}"
            </div>
        @endif

        <!-- Hobbies Pills -->
        <div class="space-y-1.5 pt-1">
            <h3 class="text-xs font-semibold text-zinc-500 uppercase tracking-wider">Hobi & Minat Utama</h3>
            <div class="flex flex-wrap gap-1.5">
                @forelse($user->hobbies as $hobby)
                    <span class="px-2.5 py-0.5 rounded-full bg-zinc-100 border border-zinc-200 text-zinc-700 text-xs font-medium">
                        #{{ $hobby->name }}
                    </span>
                @empty
                    <span class="text-xs text-zinc-400">Belum memilih hobi.</span>
                @endforelse
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex items-center gap-1.5 pt-3 border-t border-zinc-100 overflow-x-auto">
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'posts']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ (!request('tab') || request('tab') === 'posts') ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Kiriman ({{ $user->posts->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'circles']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('tab') === 'circles' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Sirkel ({{ $user->communities->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'threads']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ request('tab') === 'threads' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Utas Forum ({{ $user->threads->count() }})
            </a>
        </div>
    </div>

    <!-- TAB 1: POSTS -->
    @if(!request('tab') || request('tab') === 'posts')
        <div class="space-y-3">
            @forelse($posts as $post)
                <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-zinc-200 space-y-2.5">
                    <p class="text-xs text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                    <p class="text-xs sm:text-sm text-zinc-800 leading-relaxed">{{ $post->content }}</p>
                    @if($post->image_url)
                        <div class="rounded-lg overflow-hidden max-h-96 border border-zinc-200 bg-zinc-900">
                            <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                        </div>
                    @endif
                    <div class="pt-2 border-t border-zinc-100 flex items-center gap-4 text-xs font-medium text-zinc-500">
                        <span>{{ $post->likes_count }} Suka</span>
                        <span>{{ $post->comments_count }} Komentar</span>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-8 text-center text-zinc-400 text-xs border border-zinc-200">
                    Pengguna ini belum membagikan postingan di Nongkrong Yuk.
                </div>
            @endforelse
            <div>{{ $posts->links() }}</div>
        </div>

    <!-- TAB 2: CIRCLES -->
    @elseif(request('tab') === 'circles')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @forelse($communities as $comm)
                <a href="{{ route('communities.show', $comm->slug) }}" class="bg-white p-3.5 rounded-xl border border-zinc-200 hover:border-zinc-300 shadow-sm flex items-center gap-3 group transition-colors">
                    <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-10 h-10 rounded-lg object-cover ring-1 ring-zinc-200">
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-semibold text-zinc-950 group-hover:text-blue-600 truncate">{{ $comm->name }}</h4>
                        <p class="text-xs text-zinc-400 truncate">{{ $comm->members_count }} Anggota</p>
                    </div>
                </a>
            @empty
                <div class="col-span-full bg-white rounded-xl p-8 text-center text-zinc-400 text-xs border border-zinc-200">
                    Belum bergabung dengan sirkel mana pun.
                </div>
            @endforelse
            <div class="col-span-full">{{ $communities->links() }}</div>
        </div>

    <!-- TAB 3: THREADS -->
    @elseif(request('tab') === 'threads')
        <div class="bg-white rounded-xl shadow-sm border border-zinc-200 divide-y divide-zinc-200 overflow-hidden">
            @forelse($threads as $thread)
                <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 hover:bg-zinc-50 transition-colors group">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="font-medium text-zinc-600">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                        <span class="text-zinc-400">{{ $thread->comments_count }} Balasan</span>
                    </div>
                    <h4 class="font-semibold text-sm text-zinc-950 group-hover:text-blue-600 transition-colors">{{ $thread->title }}</h4>
                </a>
            @empty
                <div class="p-8 text-center text-zinc-400 text-xs">
                    Belum membuat utas di Tongkrongan.id.
                </div>
            @endforelse
        </div>
        <div class="pt-2">{{ $threads->links() }}</div>
    @endif

</div>
@endsection
