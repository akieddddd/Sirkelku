@auth
<div class="relative w-full mt-auto pt-3" x-data="{ userMenuOpen: false }">
    <!-- Trigger Button -->
    <button type="button" 
        @click="userMenuOpen = !userMenuOpen" 
        @click.away="userMenuOpen = false"
        class="w-full flex items-center justify-center xl:justify-between gap-3 p-2 xl:p-2.5 rounded-full hover:bg-slate-100 dark:hover:bg-zinc-800/80 transition-colors text-left group">
        
        <div class="flex items-center gap-3 min-w-0">
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" 
                class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-200 dark:ring-zinc-700 shrink-0 group-hover:ring-[#FF4500] dark:group-hover:ring-[#FF4500] transition-all">
            <div class="hidden xl:block min-w-0 flex-1">
                <p class="text-sm font-bold text-slate-800 dark:text-zinc-100 leading-tight truncate group-hover:text-[#FF4500] dark:group-hover:text-[#FF4500] transition-colors">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-slate-400 dark:text-zinc-400 font-medium truncate">
                    @<span>{{ Auth::user()->username }}</span>
                </p>
            </div>
        </div>

        <svg class="w-5 h-5 text-slate-400 dark:text-zinc-500 hidden xl:block shrink-0 group-hover:text-slate-600 dark:group-hover:text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
        </svg>
    </button>

    <!-- Upward Dropdown Menu -->
    <div x-show="userMenuOpen" x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="transform opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="transform opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="transform opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="transform opacity-0 translate-y-2 scale-95"
        class="absolute bottom-full mb-2 left-0 w-64 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-200 dark:border-zinc-800 py-1.5 z-50">
        
        <div class="px-4 py-2.5 border-b border-slate-100 dark:border-zinc-800 bg-slate-50/80 dark:bg-zinc-800/60 rounded-t-2xl">
            <p class="text-[10px] text-slate-400 dark:text-zinc-400 font-bold uppercase tracking-wider">Akun Saya</p>
            <p class="text-xs font-extrabold text-slate-800 dark:text-zinc-100 truncate">{{ Auth::user()->name }}</p>
            <p class="text-[11px] text-[#FF4500] font-medium truncate">@<span>{{ Auth::user()->username }}</span></p>
        </div>
        
        <div class="py-1">
            <a href="{{ route('profile.show', Auth::user()->username) }}" 
               class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors">
                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span>Profil Saya</span>
            </a>

            <a href="{{ route('feed.index', ['tab' => 'my_circles']) }}" 
               class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors">
                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                <span>Sirkel Saya</span>
            </a>

            <a href="{{ route('profile.edit') }}" 
               class="flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-800 hover:text-[#FF4500] dark:hover:text-[#FF4500] transition-colors">
                <svg class="w-4 h-4 text-slate-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <span>Pengaturan Akun</span>
            </a>
        </div>

        <div class="border-t border-slate-100 dark:border-zinc-800 mt-1 pt-1">
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
@else
<div class="w-full mt-auto pt-3 flex flex-col gap-2">
    <a href="{{ route('login') }}" class="w-full py-2.5 px-4 text-xs font-bold text-slate-700 dark:text-zinc-300 hover:text-[#FF4500] hover:bg-slate-100 dark:hover:bg-zinc-800 rounded-full text-center transition-colors">Masuk</a>
    <a href="{{ route('register') }}" class="w-full py-2.5 px-4 text-xs font-bold text-white bg-[#FF4500] hover:bg-[#E03E00] rounded-full text-center shadow-xs transition-colors">Daftar</a>
</div>
@endauth
