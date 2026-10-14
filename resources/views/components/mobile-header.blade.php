<!-- Mobile Top Header Component (< md) -->
<header class="md:hidden sticky top-0 z-30 h-14 bg-white border-b border-slate-200 dark:bg-zinc-950 dark:border-zinc-800 px-3 sm:px-4 flex items-center justify-between shrink-0 select-none relative transition-colors" 
        x-data="{ mobileSearchOpen: false }">
    <!-- Sisi Kiri: Tombol Hamburger + Logo Sirkelku -->
    <div class="flex items-center gap-2 min-w-0">
        <!-- Tombol Hamburger (min-w 40px touch area) -->
        <button type="button" 
                @click="mobileDrawerOpen = true" 
                class="w-10 h-10 -ml-1 flex items-center justify-center rounded-xl text-slate-700 dark:text-zinc-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors focus:outline-hidden" 
                aria-label="Buka menu navigasi">
            <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Logo Sirkelku -->
        <x-logo :href="route('feed.index')" icon-class="w-7 h-7" text-size="text-lg" aria-label="Sirkelku Beranda" />
    </div>

    <!-- Sisi Kanan: Theme Toggle + Pencarian + Avatar / Guest CTA -->
    <div class="flex items-center gap-1 shrink-0">
        <!-- Tombol Sakelar Tema Mobile -->
        <button type="button" 
                onclick="toggleTheme()" 
                class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-600 dark:text-zinc-400 hover:text-slate-900 dark:hover:text-zinc-100 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors" 
                title="Ganti Tema"
                aria-label="Ganti Tema">
            <svg class="w-4 h-4 hidden dark:block text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg class="w-4 h-4 block dark:hidden text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>

        <!-- Tombol Buka/Tutup Pencarian Mobile -->
        <button type="button" 
                @click="mobileSearchOpen = !mobileSearchOpen" 
                class="w-9 h-9 flex items-center justify-center rounded-xl text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors focus:outline-hidden" 
                aria-label="Cari sirkel atau topik">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        @auth
            <a href="{{ route('profile.show', Auth::user()->username) }}" 
               class="w-9 h-9 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors shrink-0" 
               aria-label="Profil Saya">
                <img src="{{ Auth::user()->avatar_url }}" 
                     alt="{{ Auth::user()->name }}" 
                     class="w-7 h-7 rounded-full object-cover ring-2 ring-slate-200 dark:ring-zinc-700 hover:ring-[#FF4500] transition-all">
            </a>
        @else
            <a href="{{ route('login') }}" 
               class="h-8 px-2.5 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-zinc-300 hover:text-slate-900 rounded-full transition-colors" 
               aria-label="Masuk">
                Masuk
            </a>
            <a href="{{ route('register') }}" 
               class="h-8 px-3 flex items-center justify-center text-xs font-bold text-white rounded-full bg-[#FF4500] hover:bg-[#E03E00] shadow-xs transition-colors" 
               aria-label="Daftar">
                Daftar
            </a>
        @endauth
    </div>

    <!-- Expandable Search Bar Overlay untuk Mobile -->
    <div x-show="mobileSearchOpen" 
         x-cloak 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="absolute top-14 left-0 right-0 bg-white/98 dark:bg-zinc-950/98 backdrop-blur-md border-b border-slate-200 dark:border-zinc-800 px-3 py-2.5 shadow-md z-40">
        <form action="{{ route('feed.index') }}" method="GET" class="relative flex items-center">
            <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" 
                   id="mobile-search-input"
                   aria-label="Cari sirkel, hobi, topik"
                   name="q" 
                   value="{{ request('q') }}" 
                   placeholder="Cari sirkel, hobi, topik..." 
                   class="w-full pl-9 pr-9 py-2 text-xs rounded-xl border border-slate-200 dark:border-zinc-700 bg-slate-50 dark:bg-zinc-900 focus:bg-white dark:focus:bg-zinc-900 focus:border-[#FF4500] focus:ring-1 focus:ring-[#FF4500] text-slate-800 dark:text-zinc-100 placeholder-slate-400 dark:placeholder-zinc-500 transition-all outline-none" 
                   x-ref="mobileSearchInput"
                   x-effect="if (mobileSearchOpen) $nextTick(() => $refs.mobileSearchInput.focus())">
            <button type="button" 
                    @click="mobileSearchOpen = false" 
                    class="absolute right-2.5 w-6 h-6 flex items-center justify-center text-slate-400 hover:text-slate-700 dark:hover:text-zinc-200 transition-colors"
                    aria-label="Tutup pencarian">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </form>
    </div>
</header>
