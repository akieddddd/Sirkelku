@extends('layouts.app')

@section('content')
<div class="space-y-5 p-4 sm:p-5">

    <!-- Profile Header Card -->
    <div class="sk-card space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div>
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover ring-4 ring-emerald-200/70 shadow-sm">
                </div>
                <div class="space-y-0.5">
                    <div class="flex items-center gap-2">
                        <h1 class="text-lg sm:text-xl font-black text-slate-900 dark:text-zinc-100">{{ $user->name }}</h1>
                        <span class="sk-badge-sage text-[10px]">Pelajar</span>
                        @if($user->isOnline())
                            <span class="bg-green-100 dark:bg-green-950/50 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 px-2 py-0.5 rounded-full text-[10px] font-bold">Online</span>
                        @else
                            <span class="bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700 px-2 py-0.5 rounded-full text-[10px] font-bold">Offline</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">@<span>{{ $user->username }}</span></p>
                    <p class="text-xs text-slate-600 dark:text-zinc-300 flex items-center gap-1.5 pt-1 font-medium">
                        <svg class="w-3.5 h-3.5 text-slate-500 dark:text-zinc-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>{{ $user->school ? $user->school->school_name : 'Belum memilih sekolah' }}</span>
                        @if($user->school)
                            <span class="text-slate-500 dark:text-zinc-400">({{ $user->school->city }})</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Profile Action Button -->
            <div class="flex items-center gap-2">
                @if($isOwnProfile)
                    <a href="{{ route('profile.edit') }}" class="sk-btn-outline text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Edit Profil & Hobi</span>
                    </a>
                @elseif(!auth()->check())
                    <a href="{{ route('login') }}" class="sk-btn-primary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span>+ Berteman</span>
                    </a>
                @elseif($isFriend)
                    <a href="{{ route('messages.show', $user->username) }}" class="sk-btn-outline text-xs px-4 py-2 bg-white dark:bg-zinc-800 text-slate-700 dark:text-zinc-200 border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700/60 flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                        <span>Kirim Pesan</span>
                    </a>
                    <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $user->username]) }}" class="sk-btn-primary text-xs px-4 py-2 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Ajak Main</span>
                    </a>
                @elseif($matchRequest && $matchRequest->status === 'pending')
                    @if($matchRequest->sender_id === auth()->id())
                        <button type="button" disabled class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-zinc-800 text-slate-500 dark:text-zinc-400 border border-slate-200 dark:border-zinc-700 flex items-center gap-1.5 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Menunggu Konfirmasi</span>
                        </button>
                    @else
                        <div class="flex items-center gap-1.5">
                            <form action="{{ route('matchmaking.respond', $matchRequest->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 dark:border-zinc-700 transition-all cursor-pointer">
                                    Tolak
                                </button>
                            </form>
                            <form action="{{ route('matchmaking.respond', $matchRequest->id) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="sk-btn-primary text-xs px-4 py-2 flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Terima Pertemanan</span>
                                </button>
                            </form>
                        </div>
                    @endif
                @else
                    <form action="{{ route('matchmaking.request') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="hidden" name="type" value="friend">
                        <button type="submit" class="sk-btn-primary text-xs px-4 py-2 flex items-center gap-1.5 cursor-pointer">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            <span>+ Berteman</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Bio -->
        @if($user->bio)
            <div class="bg-slate-50 dark:bg-zinc-800/60 p-3.5 rounded-xl border border-slate-200 dark:border-zinc-700 text-xs sm:text-sm text-slate-700 dark:text-zinc-200 leading-relaxed font-medium">
                "{{ $user->bio }}"
            </div>
        @endif

        <!-- Hobbies Pills -->
        <div class="space-y-1.5 pt-1">
            <h3 class="text-[11px] font-bold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">Hobi & Minat Utama</h3>
            <div class="flex flex-wrap gap-1.5">
                @forelse($user->hobbies as $hobby)
                    <span class="sk-pill text-xs">
                        #{{ $hobby->name }}
                    </span>
                @empty
                    <span class="text-xs text-slate-500 dark:text-zinc-400 italic">Belum memilih hobi.</span>
                @endforelse
            </div>
        </div>

        @if(!$isLocked)
        <!-- Tabs Navigation -->
        <div class="flex items-center gap-1.5 pt-4 border-t border-slate-200 dark:border-zinc-800 overflow-x-auto">
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'posts']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ (!request('tab') || request('tab') === 'posts') ? 'bg-[#FF4500] text-white shadow-sm' : 'text-slate-600 dark:text-zinc-300 hover:bg-orange-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500]' }}">
                Kiriman ({{ $user->posts->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'circles']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('tab') === 'circles' ? 'bg-[#FF4500] text-white shadow-sm' : 'text-slate-600 dark:text-zinc-300 hover:bg-orange-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500]' }}">
                Sirkel ({{ $user->communities->count() }})
            </a>
            <a href="{{ route('profile.show', ['username' => $user->username, 'tab' => 'threads']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ request('tab') === 'threads' ? 'bg-[#FF4500] text-white shadow-sm' : 'text-slate-600 dark:text-zinc-300 hover:bg-orange-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500]' }}">
                Utas Forum ({{ $user->threads->count() }})
            </a>
        </div>
        @endif
    </div>

    @if($isLocked)
        <!-- Locked Profile Card (Instagram Style) -->
        <div class="sk-card py-16 px-6 text-center space-y-3.5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-xs">
            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 flex items-center justify-center mx-auto text-slate-500 dark:text-zinc-400">
                <svg class="w-8 h-8 text-slate-700 dark:text-zinc-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div class="space-y-1.5 max-w-sm mx-auto">
                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-zinc-100">
                    Akun Ini Bersifat Privat
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 font-medium leading-relaxed">
                    Bertemanlah dengan pengguna ini untuk melihat cerita dan kirimannya di Sirkelku.
                </p>
            </div>
        </div>
    @else
        <!-- TAB 1: POSTS -->
        @if(!request('tab') || request('tab') === 'posts')
            <div class="space-y-3">
                @forelse($posts as $post)
                    <div class="sk-card space-y-2.5">
                        <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">{{ $post->created_at->diffForHumans() }}</p>
                        <p class="text-xs sm:text-sm text-slate-800 dark:text-zinc-200 leading-relaxed font-medium">{{ $post->content }}</p>
                        @if($post->image_url)
                            <div class="rounded-xl overflow-hidden max-h-96 border border-slate-200 dark:border-zinc-800 bg-slate-900">
                                <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                            </div>
                        @endif
                        <div class="pt-3 border-t border-slate-100 dark:border-zinc-800 flex items-center gap-4 text-xs font-semibold text-slate-500 dark:text-zinc-400">
                            <span class="flex items-center gap-1 hover:text-[#FF4500] transition-colors">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                {{ $post->likes_count }} Suka
                            </span>
                            <span class="flex items-center gap-1 hover:text-[#FF4500] transition-colors">
                                <svg class="w-3.5 h-3.5 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                {{ $post->comments_count }} Komentar
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="sk-card p-10 text-center space-y-2">
                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-400 dark:text-zinc-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">Belum ada kiriman yang dibagikan.</p>
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
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-zinc-100 group-hover:text-[#FF4500] truncate transition-colors">{{ $comm->name }}</h4>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium truncate flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                {{ $comm->members_count }} Anggota
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full sk-card p-10 text-center space-y-2">
                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-400 dark:text-zinc-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">Belum bergabung dengan sirkel mana pun.</p>
                    </div>
                @endforelse
                <div class="col-span-full">{{ $communities->links() }}</div>
            </div>

        <!-- TAB 3: THREADS -->
        @elseif(request('tab') === 'threads')
            <div class="sk-card p-0 divide-y divide-slate-100 dark:divide-zinc-800 overflow-hidden">
                @forelse($threads as $thread)
                    <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 hover:bg-emerald-50/40 dark:hover:bg-zinc-800/60 transition-colors group">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="sk-badge-sage text-[10px]">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                            <span class="text-slate-500 dark:text-zinc-400 font-medium flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                {{ $thread->comments_count }} Balasan
                            </span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-zinc-100 group-hover:text-[#FF4500] transition-colors">{{ $thread->title }}</h4>
                    </a>
                @empty
                    <div class="p-10 text-center space-y-2">
                        <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-400 dark:text-zinc-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">Belum membuat utas diskusi.</p>
                    </div>
                @endforelse
            </div>
            <div class="pt-2">{{ $threads->links() }}</div>
        @endif
    @endif

</div>
@endsection

