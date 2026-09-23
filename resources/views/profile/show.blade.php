@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Profile Header Card -->
    <div class="sk-card space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover ring-4 ring-emerald-200/70 shadow-sm">
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <h1 class="text-lg sm:text-xl font-black text-slate-900">{{ $user->name }}</h1>
                        <span class="sk-badge-sage text-[10px]">Pelajar</span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">@<span>{{ $user->username }}</span></p>
                    <p class="text-xs text-slate-600 flex items-center gap-1.5 pt-1 font-medium">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>{{ $user->school ? $user->school->school_name : 'Belum memilih sekolah' }}</span>
                        @if($user->school)
                            <span class="text-slate-400">({{ $user->school->city }})</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Profile Action Button -->
            <div class="flex gap-2">
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}" class="sk-btn-outline text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Edit Profil & Hobi</span>
                    </a>
                @else
                    <a href="{{ route('messages.show', $user->username) }}" class="sk-btn-outline text-xs px-4 py-2 bg-white text-[#588157] border-emerald-200 hover:bg-emerald-50 hover:border-emerald-300 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        <span>Kirim Pesan</span>
                    </a>
                    <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $user->username]) }}" class="sk-btn-primary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        <span>Ajak Main</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Bio -->
        @if($user->bio)
            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
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
        <!-- Profile Tabs -->
        <div class="flex items-center gap-1.5 pt-4 border-t border-slate-200 overflow-x-auto">
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'posts']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ (!request('tab') || request('tab') === 'posts') ? 'bg-[#588157] text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-[#588157]' }}">
                Kiriman ({{ $user->posts->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'circles']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('tab') === 'circles' ? 'bg-[#588157] text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-[#588157]' }}">
                Sirkel ({{ $user->communities->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'threads']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('tab') === 'threads' ? 'bg-[#588157] text-white shadow-sm' : 'text-slate-600 hover:bg-emerald-50 hover:text-[#588157]' }}">
                Utas Forum ({{ $user->threads->count() }})
            </a>
        </div>
    </div>

    <!-- TAB 1: POSTS -->
    @if(!request('tab') || request('tab') === 'posts')
        <div class="space-y-3">
            @forelse($posts as $post)
                <div class="sk-card space-y-2.5">
                    <p class="text-xs text-slate-400 font-medium">{{ $post->created_at->diffForHumans() }}</p>
                    <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">{{ $post->content }}</p>
                    @if($post->image_url)
                        <div class="rounded-xl overflow-hidden max-h-96 border border-slate-200 bg-slate-900">
                            <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                        </div>
                    @endif
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-4 text-xs font-semibold text-slate-500">
                        <span class="flex items-center gap-1 hover:text-[#588157] transition-colors">
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            {{ $post->likes_count }} Suka
                        </span>
                        <span class="flex items-center gap-1 hover:text-[#588157] transition-colors">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ $post->comments_count }} Komentar
                        </span>
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
                <a href="{{ route('communities.show', $comm->slug) }}" class="sk-card-compact flex items-center gap-3 group hover:border-emerald-300 hover:shadow-md transition-all">
                    <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-11 h-11 rounded-xl object-cover ring-2 ring-emerald-100 group-hover:ring-emerald-300">
                    <div class="min-w-0">
                        <h4 class="text-xs sm:text-sm font-bold text-slate-900 group-hover:text-[#588157] truncate transition-colors">{{ $comm->name }}</h4>
                        <p class="text-xs text-slate-400 font-medium truncate flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            {{ $comm->members_count }} Anggota
                        </p>
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
        <div class="sk-card p-0 divide-y divide-slate-100 overflow-hidden">
            @forelse($threads as $thread)
                <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 hover:bg-emerald-50/40 transition-colors group">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="sk-badge-sage text-[10px]">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                        <span class="text-slate-400 font-medium flex items-center gap-1">
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            {{ $thread->comments_count }} Balasan
                        </span>
                    </div>
                    <h4 class="font-bold text-sm text-slate-900 group-hover:text-[#588157] transition-colors">{{ $thread->title }}</h4>
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

