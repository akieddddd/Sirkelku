<!-- Mobile Navigation Drawer & Backdrop Component (< md) -->
<div>
    <!-- 1. Backdrop Overlay (Dark semi-transparent with slight blur) -->
    <div x-show="mobileDrawerOpen" 
         x-cloak
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileDrawerOpen = false"
         class="md:hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-xs select-none"
         aria-hidden="true">
    </div>

    <!-- 2. Drawer Panel (Slides in smoothly from left) -->
    <aside x-show="mobileDrawerOpen" 
           x-cloak
           x-transition:enter="transition transform ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition transform ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           @keydown.window.escape="mobileDrawerOpen = false"
           class="md:hidden fixed inset-y-0 left-0 z-50 w-72 max-w-[85vw] bg-white dark:bg-zinc-900 shadow-2xl flex flex-col justify-between overflow-hidden select-none"
           role="dialog" 
           aria-modal="true" 
           aria-label="Menu Navigasi Mobile">

        <!-- Top Section: Header & User Info -->
        <div class="p-4 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/60 dark:bg-zinc-800/50">
            <div class="flex items-start justify-between gap-3">
                @auth
                    <!-- Info Pengguna: Avatar, Nama, Username, Asal Sekolah -->
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <img src="{{ Auth::user()->avatar_url }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-12 h-12 rounded-full object-cover ring-2 ring-slate-200 dark:ring-zinc-700 shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-800 dark:text-zinc-100 leading-tight truncate">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-xs text-slate-400 dark:text-zinc-400 font-medium truncate mt-0.5">
                                {{ '@' . Auth::user()->username }}
                            </p>
                            @if(Auth::user()->school)
                                <p class="text-[11px] text-slate-500 dark:text-zinc-400 font-medium flex items-center gap-1.5 mt-1 truncate">
                                    <svg class="w-3.5 h-3.5 text-slate-400 dark:text-zinc-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="truncate">{{ Auth::user()->school->school_name }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="min-w-0 flex-1">
                        <x-logo :href="route('feed.index')" icon-class="w-8 h-8" text-size="text-lg" />
                    </div>
                @endauth

                <!-- Tombol Tutup (X) -->
                <button type="button" 
                        @click="mobileDrawerOpen = false" 
                        class="w-10 h-10 -mr-1 -mt-1 flex items-center justify-center rounded-full text-slate-400 dark:text-zinc-400 hover:text-slate-700 dark:hover:text-zinc-100 hover:bg-slate-200/60 dark:hover:bg-zinc-800 transition-colors shrink-0" 
                        aria-label="Tutup menu navigasi">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Search Input di Drawer (Akses Cepat Pencarian Mobile) -->
        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-zinc-800 bg-white dark:bg-zinc-900">
            <form action="{{ route('communities.index') }}" method="GET" class="relative flex items-center">
                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       id="drawer-search-input"
                       aria-label="Cari sirkel atau topik"
                       name="q" 
                       value="{{ request('q') }}" 
                       placeholder="Cari sirkel atau topik..." 
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-800/80 focus:bg-white dark:focus:bg-zinc-900 focus:border-[#FF4500] focus:ring-1 focus:ring-[#FF4500] text-slate-800 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 transition-all outline-none">
            </form>
        </div>

        <!-- Middle Section: Daftar Navigasi -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-1">
            <!-- 1. Nongkrong Yuk (Feed) -->
            <a href="{{ route('feed.index') }}" 
               @click="mobileDrawerOpen = false" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm transition-all duration-150 {{ (request()->routeIs('feed.*') && !in_array(request('tab'), ['saved', 'liked'])) ? 'bg-[#FFF0E6] text-[#FF4500] dark:bg-[#FF4500]/15 dark:text-[#FF4500] font-bold' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span class="truncate">Nongkrong Yuk</span>
            </a>

            <!-- 2. Satu Sirkel (Komunitas) -->
            <a href="{{ route('communities.index') }}" 
               @click="mobileDrawerOpen = false" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm transition-all duration-150 {{ request()->routeIs('communities.*') ? 'bg-[#FFF0E6] text-[#FF4500] dark:bg-[#FF4500]/15 dark:text-[#FF4500] font-bold' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="truncate">Satu Sirkel</span>
            </a>

            <!-- 3. Tongkrongan.id (Forum Diskusi) -->
            <a href="{{ route('threads.index') }}" 
               @click="mobileDrawerOpen = false" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm transition-all duration-150 {{ request()->routeIs('threads.*') ? 'bg-[#FFF0E6] text-[#FF4500] dark:bg-[#FF4500]/15 dark:text-[#FF4500] font-bold' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <span class="truncate">Tongkrongan.id</span>
            </a>

            <!-- 4. Teman Main (Matchmaking) -->
            <a href="{{ route('matchmaking.index') }}" 
               @click="mobileDrawerOpen = false" 
               class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm transition-all duration-150 {{ request()->routeIs('matchmaking.*') ? 'bg-[#FFF0E6] text-[#FF4500] dark:bg-[#FF4500]/15 dark:text-[#FF4500] font-bold' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="truncate">Teman Main</span>
            </a>

            @auth
                <!-- 5. Pesan (Direct Messages) -->
                <a href="{{ route('messages.index') }}" 
                   @click="mobileDrawerOpen = false" 
                   class="flex items-center justify-between gap-3.5 px-3.5 py-3 rounded-2xl text-sm transition-all duration-150 {{ request()->routeIs('messages.*') ? 'bg-[#FFF0E6] text-[#FF4500] dark:bg-[#FF4500]/15 dark:text-[#FF4500] font-bold' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span class="truncate">Pesan</span>
                    </div>
                    @if(isset($unreadMessagesCount) && $unreadMessagesCount > 0)
                        <span class="min-w-5 h-5 px-1.5 bg-[#FF4500] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center">
                            {{ $unreadMessagesCount > 9 ? '9+' : $unreadMessagesCount }}
                        </span>
                    @endif
                </a>

                <!-- 6. Notifikasi (Tidak ada di bottom navigation, jadi disediakan di drawer) -->
                <a href="{{ route('notifications.index') }}" 
                   @click="mobileDrawerOpen = false" 
                   class="flex items-center justify-between gap-3.5 px-3.5 py-3 rounded-2xl text-sm transition-all duration-150 {{ request()->routeIs('notifications.*') ? 'bg-[#FFF0E6] text-[#FF4500] dark:bg-[#FF4500]/15 dark:text-[#FF4500] font-bold' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="truncate">Notifikasi</span>
                    </div>
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="min-w-5 h-5 px-1.5 bg-[#FF4500] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- 7. Profil Saya -->
                <a href="{{ route('profile.show', Auth::user()->username) }}" 
                   @click="mobileDrawerOpen = false" 
                   class="flex items-center gap-3.5 px-3.5 py-3 rounded-2xl text-sm transition-all duration-150 {{ request()->is('profile/' . Auth::user()->username) ? 'bg-[#FFF0E6] text-[#FF4500] dark:bg-[#FF4500]/15 dark:text-[#FF4500] font-bold' : 'text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 hover:text-slate-900 dark:hover:text-white font-semibold' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="truncate">Profil Saya</span>
                </a>
            @endauth
        </nav>

        <!-- Bottom Section: Tombol Keluar (atau Masuk / Daftar jika guest) -->
        <div class="p-3 border-t border-slate-100 dark:border-zinc-800 bg-slate-50/50 dark:bg-zinc-800/40">
            @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 font-bold text-sm transition-colors cursor-pointer" 
                            aria-label="Keluar">
                        <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            @else
                <div class="flex flex-col gap-2">
                    <a href="{{ route('login') }}" 
                       class="w-full py-2.5 px-4 text-xs font-bold text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-full text-center transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" 
                       class="w-full py-2.5 px-4 text-xs font-bold text-white bg-[#FF4500] hover:bg-[#E03E00] rounded-full text-center shadow-xs transition-colors">
                        Daftar
                    </a>
                </div>
            @endauth
        </div>
    </aside>
</div>
