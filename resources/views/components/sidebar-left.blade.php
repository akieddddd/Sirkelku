<aside class="hidden md:flex flex-col justify-between h-full overflow-y-auto custom-scrollbar md:w-20 xl:w-64 shrink-0 py-3 px-2 xl:px-4 select-none transition-colors">
    <!-- Top Nav / Value Card Section -->
    <div class="space-y-3 w-full">
        @guest
            <!-- Reddit-Style Value Card Pelajar for Guests -->
            <div class="hidden xl:block bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-3xl p-5 shadow-xs space-y-4">
                <div class="space-y-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider text-[#FF4500] bg-[#FFF0E6] dark:bg-[#FF4500]/15 border border-[#F0D5C6] dark:border-[#FF4500]/30">
                        Platform Pelajar
                    </span>
                    <h3 class="font-black text-sm text-slate-900 dark:text-zinc-100 leading-snug">
                        Tempat Nongkrong Siswa Se-Indonesia
                    </h3>
                </div>

                <!-- 3 Benefit Points with clean SVG / Lucide Icons (No Emojis) -->
                <div class="space-y-3 text-xs text-slate-600 dark:text-zinc-300">
                    <!-- Point 1: Gamepad Icon -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-orange-50 dark:bg-zinc-800 text-[#FF4500] flex items-center justify-center shrink-0 mt-0.5 border border-orange-100 dark:border-zinc-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="leading-relaxed">Temukan teman mabar & sirkel sehobi.</span>
                    </div>

                    <!-- Point 2: School / Building Icon -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 dark:bg-zinc-800 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 border border-emerald-100 dark:border-zinc-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <span class="leading-relaxed">Diskusi PR & info sekolah se-nusantara.</span>
                    </div>

                    <!-- Point 3: Chat / Message-Square Icon -->
                    <div class="flex items-start gap-2.5">
                        <div class="w-7 h-7 rounded-lg bg-sky-50 dark:bg-zinc-800 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0 mt-0.5 border border-sky-100 dark:border-zinc-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        </div>
                        <span class="leading-relaxed">Ngobrol santai tanpa penghakiman.</span>
                    </div>
                </div>

                <!-- Call to Action Buttons -->
                <div class="space-y-2 pt-1">
                    <!-- Tombol Utama Oranye -->
                    <a href="{{ route('register') }}" 
                       class="w-full flex items-center justify-center py-2.5 px-4 bg-[#FF4500] hover:bg-[#E03E00] text-white font-bold text-xs rounded-full shadow-xs hover:shadow-md transition-all text-center">
                        Daftar Sekarang
                    </a>
                    <!-- Tombol Sekunder Outline -->
                    <a href="{{ route('login') }}" 
                       class="w-full flex items-center justify-center py-2.5 px-4 border border-slate-300 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800 font-bold text-xs rounded-full transition-colors text-center">
                        Sudah punya akun? Masuk
                    </a>
                </div>

                <!-- Footer Kartu: Deretan avatar mini robot style -->
                <div class="pt-3 border-t border-slate-100 dark:border-zinc-800/80 flex items-center gap-2">
                    <div class="flex -space-x-1.5 overflow-hidden shrink-0">
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-orange-100 object-cover" src="https://api.dicebear.com/7.x/bottts/svg?seed=dika_pratama" alt="Avatar">
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-emerald-100 object-cover" src="https://api.dicebear.com/7.x/bottts/svg?seed=clara_indie" alt="Avatar">
                        <img class="inline-block h-6 w-6 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-sky-100 object-cover" src="https://api.dicebear.com/7.x/bottts/svg?seed=keiko_art" alt="Avatar">
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-zinc-400 font-medium leading-tight">
                        500+ siswa sudah bergabung.
                    </p>
                </div>
            </div>

            <!-- Compact mode for tablets (< xl) -->
            <div class="xl:hidden flex flex-col items-center gap-2 py-4">
                <a href="{{ route('register') }}" 
                   class="w-11 h-11 rounded-full bg-[#FF4500] hover:bg-[#E03E00] text-white flex items-center justify-center shadow-md transition-all hover:scale-105"
                   title="Daftar Akun Baru">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </a>
                <a href="{{ route('login') }}" 
                   class="w-11 h-11 rounded-full border border-slate-300 dark:border-zinc-700 hover:bg-slate-100 dark:hover:bg-zinc-800 text-slate-700 dark:text-zinc-300 flex items-center justify-center transition-all hover:scale-105"
                   title="Masuk ke Akun">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                </a>
            </div>
        @else
            <!-- 2. Vertical Navigation Menu for Authenticated Users -->
            <nav class="space-y-1">
                <!-- Nongkrong Yuk (Feed) -->
                <x-nav-item 
                    :href="route('feed.index')" 
                    :active="request()->routeIs('feed.*') && !in_array(request('tab'), ['saved', 'liked'])" 
                    label="Nongkrong Yuk">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </x-nav-item>

                <!-- Satu Sirkel (Direktori Komunitas) -->
                <x-nav-item 
                    :href="route('communities.index')" 
                    :active="request()->routeIs('communities.*')" 
                    label="Satu Sirkel">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </x-nav-item>

                <!-- Tongkrongan.id (Forum Diskusi) -->
                <x-nav-item 
                    :href="route('threads.index')" 
                    :active="request()->routeIs('threads.*')" 
                    label="Tongkrongan.id">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </x-nav-item>

                <!-- Teman Main (Matchmaking) -->
                <x-nav-item 
                    :href="route('matchmaking.index')" 
                    :active="request()->routeIs('matchmaking.*')" 
                    label="Teman Main">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-nav-item>

                <!-- Disimpan (Bookmarks) -->
                <x-nav-item 
                    :href="route('feed.index', ['tab' => 'saved'])" 
                    :active="request('tab') === 'saved'" 
                    onclick="openSavedPosts(event)"
                    label="Disimpan">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </x-nav-item>

                <!-- Disukai (Liked Posts) -->
                <x-nav-item 
                    :href="route('feed.index', ['tab' => 'liked'])" 
                    :active="request('tab') === 'liked'" 
                    label="Disukai">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </x-nav-item>

                <!-- Pesan (Direct Messages) -->
                <x-nav-item 
                    :href="route('messages.index')" 
                    :active="request()->routeIs('messages.*')" 
                    label="Pesan"
                    :badge="$unreadMessagesCount ?? 0"
                    badgeClass="nav-msg-badge">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </x-nav-item>

                <!-- Notifikasi -->
                <x-nav-item 
                    :href="route('notifications.index')" 
                    :active="request()->routeIs('notifications.*')" 
                    label="Notifikasi"
                    :badge="$unreadCount ?? 0"
                    badgeClass="nav-notif-badge">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </x-nav-item>

                <!-- Profil Saya -->
                <x-nav-item 
                    :href="route('profile.show', Auth::user()->username)" 
                    :active="request()->is('profile/' . Auth::user()->username)" 
                    label="Profil Saya">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </x-nav-item>

                <!-- Pengaturan -->
                <x-nav-item 
                    :href="route('profile.edit')" 
                    :active="request()->routeIs('profile.edit')" 
                    label="Pengaturan">
                    <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </x-nav-item>
            </nav>

            <!-- 3. Primary CTA Button: Sirkel Baru -->
            <div class="pt-4">
                <!-- Expanded mode (>=xl) -->
                <a href="{{ route('communities.create') }}" 
                   class="hidden xl:flex w-full py-3 px-4 bg-[#FF4500] hover:bg-[#E03E00] text-white font-bold text-sm rounded-full shadow-sm hover:shadow-md transition-all items-center justify-center gap-2 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Sirkel Baru</span>
                </a>

                <!-- Collapsed mode (<xl) -->
                <a href="{{ route('communities.create') }}" 
                   class="xl:hidden w-12 h-12 rounded-full bg-[#FF4500] hover:bg-[#E03E00] text-white shadow-sm flex items-center justify-center transition-all hover:scale-105 mx-auto"
                   title="Sirkel Baru">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                    </svg>
                </a>
            </div>
        @endguest
    </div>

    <!-- 4. Mini User Card (mt-auto, bottom anchor for Auth) -->
    @auth
        <x-user-mini-card />
    @endauth
</aside>

<script>
    function openSavedPosts(e) {
        e.preventDefault();
        const saved = JSON.parse(localStorage.getItem('sirkelku_saved_posts') || '[]');
        const ids = saved.join(',');
        window.location.href = "{{ route('feed.index') }}?tab=saved" + (ids ? "&saved_ids=" + ids : "");
    }
</script>
