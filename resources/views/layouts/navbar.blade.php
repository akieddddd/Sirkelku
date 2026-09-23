<!-- Top Navigation Header Component -->
<header class="sk-navbar sticky top-0 z-40 w-full bg-white/95 backdrop-blur-md border-b border-orange-100/80 transition-all shadow-xs" x-data="{ searchOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
        
        <!-- 1. Brand Logo + Name + User Role Badge -->
        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('feed.index') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo" class="w-9 h-9 object-contain group-hover:scale-105 transition-transform duration-200">
                <div class="flex items-center gap-1.5">
                    <span class="text-xl font-black tracking-tight text-slate-900 group-hover:text-orange-600 transition-colors">Sirkelku</span>
                    <span class="hidden sm:inline-block text-[10px] font-extrabold text-orange-700 bg-orange-50 border border-orange-200 px-2 py-0.5 rounded-full">Pelajar</span>
                </div>
            </a>
        </div>

        <!-- 2. Global Search Bar (Desktop: ≥768px) -->
        <div class="hidden md:block flex-1 max-w-md mx-4">
            <form action="{{ route('communities.index') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari sirkel, hobi, atau teman mabar..." 
                    class="sk-input-search pl-9 pr-4 py-2 text-xs w-full">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>

        <!-- 3. Right Side Actions -->
        <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            
            <!-- Expandable Search Toggle for Mobile (<768px) -->
            <button type="button" @click="searchOpen = !searchOpen" class="md:hidden p-2 rounded-xl text-slate-600 hover:text-orange-600 hover:bg-orange-50 transition-colors" title="Cari">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </button>

            <!-- + Sirkel Baru Button -->
            <a href="{{ route('communities.create') }}" class="sk-btn-primary text-xs py-2 px-3.5 flex items-center gap-1.5 shrink-0" title="Bikin Sirkel Baru">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                <span class="hidden sm:inline">Sirkel Baru</span>
            </a>

            @auth
                <!-- Messages -->
                <a href="{{ route('messages.index') }}" class="relative p-2 rounded-xl text-slate-600 hover:text-orange-600 hover:bg-orange-50 transition-colors flex items-center justify-center shrink-0" title="Pesan Pribadi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </a>

                <!-- Notification Bell -->
                <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl text-slate-600 hover:text-orange-600 hover:bg-orange-50 transition-colors flex items-center justify-center shrink-0" title="Pusat Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if(isset($unreadCount) && $unreadCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-orange-500 text-white text-[10px] font-extrabold rounded-full flex items-center justify-center ring-2 ring-white">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <!-- User Avatar & Dropdown Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 p-1 rounded-xl hover:bg-orange-50 transition-all border border-transparent hover:border-orange-100">
                        <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-orange-400">
                        <div class="hidden lg:block text-left">
                            <p class="text-xs font-bold text-slate-900 leading-tight max-w-[110px] truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-orange-600 font-semibold truncate">@<span>{{ Auth::user()->username }}</span></p>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    <!-- Dropdown Modal -->
                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-orange-100 py-1.5 z-50">
                        
                        <div class="px-4 py-2.5 border-b border-orange-100 bg-orange-50/50 rounded-t-2xl">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Akun Saya</p>
                            <p class="text-xs font-extrabold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-orange-600 font-medium truncate">@<span>{{ Auth::user()->username }}</span></p>
                        </div>
                        
                        <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                            👤 Profil Saya
                        </a>

                        <a href="{{ route('feed.index', ['tab' => 'my_circles']) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                            ⭕ Sirkel Saya
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                            ⚙️ Pengaturan Akun
                        </a>

                        <div class="border-t border-orange-100 mt-1 pt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                    🚪 Keluar Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-orange-600 px-3.5 py-2 rounded-xl hover:bg-orange-50 transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="sk-btn-primary text-xs py-2 px-4">Daftar</a>
            @endauth
        </div>

        <!-- Search Bar Overlay for Mobile (<768px) -->
        <div x-show="searchOpen" x-cloak 
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="absolute top-16 left-0 right-0 bg-white border-b border-orange-100 p-3 shadow-md md:hidden z-40">
            <form action="{{ route('communities.index') }}" method="GET" class="relative max-w-lg mx-auto">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari sirkel, hobi, atau teman mabar..." 
                    class="sk-input-search pl-9 pr-9 py-2 text-xs w-full" autofocus>
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <button type="button" @click="searchOpen = false" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 text-sm font-bold">
                    &times;
                </button>
            </form>
        </div>

    </div>
</header>
