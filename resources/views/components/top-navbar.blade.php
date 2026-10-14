<!-- Top Navbar Component (Desktop & Tablet: >= md) -->
<header class="hidden md:block sticky top-0 z-40 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-md border-b border-slate-200 dark:border-zinc-800 select-none transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between gap-4">
        
        <!-- Sisi Kiri: Logo dan Nama Brand Sirkelku -->
        <div class="flex items-center gap-3 shrink-0">
            <x-logo :href="route('feed.index')" icon-class="w-9 h-9" text-size="text-xl" show-badge="true" />
        </div>

        <!-- Sisi Tengah: Input Pencarian Bentuk Kapsul -->
        <div class="flex-1 max-w-lg mx-auto">
            <form action="{{ route('feed.index') }}" method="GET" class="relative w-full group">
                <div class="relative flex items-center">
                    <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500 absolute left-3.5 pointer-events-none group-focus-within:text-[#FF4500] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari di Sirkelku..." 
                           class="w-full bg-slate-100 hover:bg-slate-200/60 dark:bg-zinc-900 dark:hover:bg-zinc-800/80 focus:bg-white dark:focus:bg-zinc-900 text-xs text-slate-800 dark:text-zinc-100 placeholder:text-slate-400 dark:placeholder:text-zinc-500 pl-10 pr-4 py-2.5 rounded-full border border-slate-200 dark:border-zinc-800 focus:border-[#FF4500] dark:focus:border-[#FF4500] focus:ring-2 focus:ring-[#FF4500]/20 outline-none transition-all font-medium">
                </div>
            </form>
        </div>

        <!-- Sisi Kanan: Theme Toggle, Notifications/Messages or Guest Login/Register -->
        <div class="flex items-center gap-2.5 shrink-0">
            <!-- Tombol Sakelar Tema (Dark & Light Mode Toggle) -->
            <button type="button" 
                    onclick="toggleTheme()" 
                    class="w-9 h-9 flex items-center justify-center rounded-full text-slate-600 hover:text-slate-900 dark:text-zinc-400 dark:hover:text-zinc-100 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors" 
                    title="Ganti Tema (Terang / Gelap)"
                    aria-label="Ganti Tema">
                <!-- Sun Icon (Shown in Dark Mode) -->
                <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon Icon (Shown in Light Mode) -->
                <svg class="w-5 h-5 block dark:hidden text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            @guest
                <!-- Guest Actions: Masuk & Daftar -->
                <a href="{{ route('login') }}" 
                   class="text-xs font-bold text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] dark:hover:text-[#FF4500] px-3.5 py-2 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" 
                   class="text-xs font-bold text-white bg-[#FF4500] hover:bg-[#E03E00] px-4 py-2 rounded-full shadow-xs transition-all hover:scale-105">
                    Daftar
                </a>
            @else
                <!-- Authenticated Actions: Notifikasi, Pesan (DM), Profil Dropdown -->
                <!-- Icon Notifikasi -->
                <a href="{{ route('notifications.index') }}" 
                   class="relative w-9 h-9 flex items-center justify-center rounded-full text-slate-600 hover:text-slate-900 dark:text-zinc-400 dark:hover:text-zinc-100 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors"
                   title="Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="nav-notif-badge absolute -top-0.5 -right-0.5 min-w-[1.125rem] h-[1.125rem] px-1 bg-[#FF4500] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center {{ ($unreadCount ?? 0) > 0 ? '' : 'hidden' }}">
                        {{ ($unreadCount ?? 0) > 9 ? '9+' : ($unreadCount ?? 0) }}
                    </span>
                </a>

                <!-- Icon Pesan (DM) -->
                <a href="{{ route('messages.index') }}" 
                   class="relative w-9 h-9 flex items-center justify-center rounded-full text-slate-600 hover:text-slate-900 dark:text-zinc-400 dark:hover:text-zinc-100 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors"
                   title="Pesan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span class="nav-msg-badge absolute -top-0.5 -right-0.5 min-w-[1.125rem] h-[1.125rem] px-1 bg-[#FF4500] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center {{ ($unreadMessagesCount ?? 0) > 0 ? '' : 'hidden' }}">
                        {{ ($unreadMessagesCount ?? 0) > 9 ? '9+' : ($unreadMessagesCount ?? 0) }}
                    </span>
                </a>

                <!-- Mini Avatar Profil Dropdown -->
                <div class="relative ml-1" x-data="{ dropdownOpen: false }">
                    <button type="button" 
                            @click="dropdownOpen = !dropdownOpen" 
                            @click.away="dropdownOpen = false"
                            class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors focus:outline-none">
                        <img src="{{ Auth::user()->avatar_url }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-200 dark:ring-zinc-700 hover:ring-[#FF4500] dark:hover:ring-[#FF4500] transition-all">
                        <svg class="w-3.5 h-3.5 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="dropdownOpen" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-200 dark:border-zinc-800 py-1.5 z-50">
                        
                        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/70 dark:bg-zinc-800/40 rounded-t-2xl">
                            <p class="text-xs font-black text-slate-800 dark:text-zinc-100 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-[#FF4500] font-semibold truncate">@<span>{{ Auth::user()->username }}</span></p>
                        </div>

                        <div class="py-1">
                            <a href="{{ route('profile.show', Auth::user()->username) }}" 
                               class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors">
                                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                <span>Profil Saya</span>
                            </a>
                            <a href="{{ route('profile.edit') }}" 
                               class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors">
                                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span>Pengaturan Akun</span>
                            </a>
                        </div>

                        <div class="border-t border-slate-100 dark:border-zinc-800 pt-1 mt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    <span>Keluar Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>
