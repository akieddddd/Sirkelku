<aside class="hidden lg:block w-80 shrink-0 h-full overflow-y-auto custom-scrollbar pl-6 py-4 space-y-4 transition-colors">
    <!-- 1. Search Bar (Fallback if top navbar is hidden / or quick widget search) -->
    <div class="relative">
        <form action="{{ route('feed.index') }}" method="GET" class="relative flex items-center group">
            <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500 absolute left-3.5 pointer-events-none group-focus-within:text-[#FF4500] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Cari di Sirkelku..." 
                   class="w-full bg-slate-100 hover:bg-slate-200/60 dark:bg-zinc-900 dark:hover:bg-zinc-800/80 focus:bg-white dark:focus:bg-zinc-900 text-xs text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-500 pl-10 pr-4 py-2.5 rounded-full border border-slate-200 dark:border-zinc-800 focus:border-[#FF4500] dark:focus:border-[#FF4500] focus:ring-2 focus:ring-[#FF4500]/20 outline-none transition-all font-medium">
        </form>
    </div>

    <!-- 2. Widget: Sirkel Terpopuler (PRD Section 2C) -->
    <div class="bg-slate-50/90 dark:bg-zinc-900 rounded-3xl p-4 border border-slate-200/80 dark:border-zinc-800 space-y-3 shadow-2xs transition-colors">
        <div class="flex items-center justify-between border-b border-slate-200/60 dark:border-zinc-800 pb-2.5">
            <h3 class="font-black text-xs uppercase tracking-wider text-slate-800 dark:text-zinc-100 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#FF4500]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.17 2.14-1.393 3.633a6.837 6.837 0 00-1.782-.977 1 1 0 00-1.25.437A9.972 9.972 0 005 10c0 4.418 3.582 8 8 8a7.994 7.994 0 005.657-2.343A7.992 7.992 0 0020 10a9.97 9.97 0 00-.773-3.856 1 1 0 00-1.25-.437 6.83 6.83 0 00-1.782.977c-.223-1.493-.866-2.813-1.393-3.633-.208-.322-.477-.65-.822-.88a1 1 0 00-.585-.118zM12 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                Sirkel Terpopuler
            </h3>
            <a href="{{ route('communities.index') }}" class="text-xs font-bold text-[#FF4500] hover:text-[#E03E00] transition-colors">Jelajah</a>
        </div>

        <div class="space-y-2.5">
            @forelse($trendingCommunities ?? [] as $index => $comm)
                <div class="flex items-center justify-between gap-2.5 p-1.5 rounded-2xl hover:bg-white dark:hover:bg-zinc-800 transition-colors group">
                    <a href="{{ route('communities.show', $comm->slug) }}" class="flex items-center gap-2.5 min-w-0 flex-1">
                        <span class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-extrabold shrink-0 {{ $index === 0 ? 'bg-[#FFF0E6] text-[#FF4500] border border-[#F0D5C6]' : 'bg-slate-200/70 dark:bg-zinc-800 text-slate-600 dark:text-zinc-400' }}">
                            {{ $index + 1 }}
                        </span>
                        <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-zinc-700 shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-slate-800 dark:text-zinc-100 group-hover:text-[#FF4500] transition-colors truncate">{{ $comm->name }}</p>
                            <p class="text-[11px] text-slate-400 dark:text-zinc-500 font-medium truncate">{{ $comm->members_count }} Anggota • {{ $comm->hobby ? $comm->hobby->name : 'Umum' }}</p>
                        </div>
                    </a>
                    <!-- Tombol Preview Sirkel -->
                    <a href="{{ route('communities.show', $comm->slug) }}" 
                       class="shrink-0 px-2.5 py-1 rounded-full text-[11px] font-bold border border-slate-200 dark:border-zinc-700 hover:border-[#FF4500] dark:hover:border-[#FF4500] text-slate-600 dark:text-zinc-300 hover:text-[#FF4500] transition-colors"
                       title="Lihat Sirkel">
                        Lihat
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2 font-medium">Belum ada sirkel terdaftar.</p>
            @endforelse
        </div>
    </div>

    <!-- 3. Widget: Topik Hangat (PRD Section 2C) -->
    <div class="bg-slate-50/90 dark:bg-zinc-900 rounded-3xl p-4 border border-slate-200/80 dark:border-zinc-800 space-y-3 shadow-2xs transition-colors">
        <div class="flex items-center justify-between border-b border-slate-200/60 dark:border-zinc-800 pb-2.5">
            <h3 class="font-black text-xs uppercase tracking-wider text-slate-800 dark:text-zinc-100 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                Topik Hangat
            </h3>
            <a href="{{ route('threads.index', ['sort' => 'trending']) }}" class="text-xs font-bold text-[#FF4500] hover:text-[#E03E00] transition-colors">Forum</a>
        </div>

        <div class="space-y-2.5">
            @forelse($hotThreads ?? [] as $thread)
                <a href="{{ route('threads.show', $thread->id) }}" class="block p-2 rounded-2xl hover:bg-white dark:hover:bg-zinc-800 transition-colors group">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="inline-flex items-center text-[10px] font-bold text-[#FF4500] bg-[#FFF0E6] dark:bg-[#FF4500]/15 border border-[#F0D5C6] dark:border-[#FF4500]/30 px-2 py-0.5 rounded-full">
                            #{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}
                        </span>
                        <span class="text-[10px] text-slate-400 dark:text-zinc-500 font-semibold">{{ $thread->comments_count }} balasan</span>
                    </div>
                    <p class="text-xs font-bold text-slate-800 dark:text-zinc-100 group-hover:text-[#FF4500] line-clamp-2 transition-colors">
                        {{ $thread->title }}
                    </p>
                </a>
            @empty
                <!-- Fallback Trending Topic Pills if no threads -->
                <div class="flex flex-wrap gap-1.5 py-1">
                    <a href="{{ route('feed.index', ['q' => 'Valorant']) }}" class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] hover:bg-orange-50 dark:hover:bg-zinc-700 transition-colors">#Valorant</a>
                    <a href="{{ route('feed.index', ['q' => 'Fotografi']) }}" class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] hover:bg-orange-50 dark:hover:bg-zinc-700 transition-colors">#Fotografi</a>
                    <a href="{{ route('feed.index', ['q' => 'Web Development']) }}" class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] hover:bg-orange-50 dark:hover:bg-zinc-700 transition-colors">#WebDev</a>
                    <a href="{{ route('feed.index', ['q' => 'Musik']) }}" class="text-xs font-bold px-3 py-1 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] hover:bg-orange-50 dark:hover:bg-zinc-700 transition-colors">#Musik</a>
                </div>
            @endforelse
        </div>
    </div>

    @auth
        <!-- Widget: Rekomendasi Teman (Bonus for Authenticated Users) -->
        <div class="bg-slate-50/90 dark:bg-zinc-900 rounded-3xl p-4 border border-slate-200/80 dark:border-zinc-800 space-y-3 shadow-2xs transition-colors">
            <div class="flex items-center justify-between border-b border-slate-200/60 dark:border-zinc-800 pb-2.5">
                <h3 class="font-black text-xs uppercase tracking-wider text-slate-800 dark:text-zinc-100 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    Teman Sefrekuensi
                </h3>
                <a href="{{ route('matchmaking.index') }}" class="text-xs font-bold text-[#FF4500] hover:text-[#E03E00] transition-colors">Semua</a>
            </div>

            <div class="space-y-2">
                @forelse($suggestedFriends ?? [] as $friend)
                    <div class="flex items-center justify-between gap-3 p-1.5 rounded-2xl hover:bg-white dark:hover:bg-zinc-800 transition-colors">
                        <a href="{{ route('profile.show', $friend->username) }}" class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-200 dark:ring-zinc-700 shrink-0">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 dark:text-zinc-100 truncate hover:text-[#FF4500] transition-colors">{{ $friend->name }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-zinc-500 font-medium truncate">
                                    {{ $friend->school ? $friend->school->city : 'Pelajar' }}
                                </p>
                            </div>
                        </a>
                        <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $friend->username]) }}" 
                           class="shrink-0 w-7 h-7 rounded-full border border-slate-200 dark:border-zinc-700 hover:bg-[#FF4500] hover:text-white hover:border-[#FF4500] text-[#FF4500] flex items-center justify-center transition-all text-xs" 
                           title="Ajak Main">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        </a>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 dark:text-zinc-500 text-center py-2 font-medium">Belum ada rekomendasi baru.</p>
                @endforelse
            </div>
        </div>
    @endauth

    <!-- 4. Footer Informasi Ringkas & Hak Cipta (PRD Section 2C) -->
    <div class="px-2 py-3 text-[11px] text-slate-400 dark:text-zinc-500 space-y-1.5 leading-relaxed font-medium">
        <div class="flex flex-wrap gap-x-3 gap-y-1">
            <a href="{{ route('feed.index') }}" class="hover:underline">Tentang</a>
            <a href="{{ route('communities.index') }}" class="hover:underline">Sirkel</a>
            <a href="{{ route('threads.index') }}" class="hover:underline">Forum</a>
            <a href="{{ route('matchmaking.index') }}" class="hover:underline">Teman Main</a>
        </div>
        <p class="text-[10px] text-slate-400/80 dark:text-zinc-600">
            &copy; {{ date('Y') }} Sirkelku. Tongkrongan digital pelajar Indonesia.
        </p>
    </div>
</aside>
