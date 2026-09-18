<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 text-slate-900 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sirkelku - Platform Jejaring Sosial & Komunitas Pelajar' }}</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js for lightweight micro-interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-slate-100 selection:bg-indigo-500 selection:text-white" x-data="{ mobileMenuOpen: false, quickPostModal: false }">

    <!-- Top Navigation Header -->
    <header class="sticky top-0 z-40 w-full border-b border-slate-200/80 bg-white/90 backdrop-blur-md transition-all shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
            
            <!-- Brand Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ route('feed.index') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-violet-600 to-pink-500 flex items-center justify-center text-white font-black text-xl shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                        S
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Sirkelku</span>
                        <span class="hidden sm:inline-block ml-1 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">Pelajar</span>
                    </div>
                </a>
            </div>

            <!-- Global Search Bar -->
            <div class="flex-1 max-w-md hidden md:block">
                <form action="{{ route('communities.index') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari sirkel, hobi, atau teman baru..." 
                        class="w-full pl-10 pr-4 py-2 bg-slate-100 hover:bg-slate-50 focus:bg-white text-sm rounded-xl border border-transparent focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 focus:outline-none transition-all placeholder:text-slate-400">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>
            </div>

            <!-- Right Actions: Notifications & User Avatar -->
            <div class="flex items-center gap-2 sm:gap-4">
                @auth
                    <!-- Notification Bell with Counter -->
                    <div class="relative" x-data="{ open: false }">
                        <a href="{{ route('notifications.index') }}" class="relative p-2 rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-100 transition-colors flex items-center justify-center" title="Pusat Notifikasi">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-pink-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center ring-2 ring-white animate-pulse">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </div>

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ userMenu: false }">
                        <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex items-center gap-2.5 p-1 rounded-xl hover:bg-slate-100 transition-all">
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-indigo-500/20">
                            <div class="hidden lg:block text-left">
                                <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-indigo-600 font-medium">@<span>{{ Auth::user()->username }}</span></p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="userMenu" x-cloak 
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400">Masuk sebagai</p>
                                <p class="text-sm font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ Auth::user()->school ? Auth::user()->school->school_name : 'Belum pilih sekolah' }}</p>
                            </div>
                            
                            <a href="{{ route('profile.show', Auth::user()->username) }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                Profil Saya
                            </a>

                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Pengaturan Akun
                            </a>

                            <div class="border-t border-slate-100 mt-1 pt-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Keluar Akun
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 hover:text-indigo-600 px-3 py-2">Masuk</a>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-xl shadow-sm transition-all">Daftar Akun</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Messages (Toasts / Banners) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-4 mb-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center justify-between shadow-sm animate-fade-in" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-medium flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="p-4 mb-4 rounded-2xl bg-sky-50 border border-sky-200 text-sky-800 text-sm font-medium flex items-center justify-between shadow-sm" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                    <span>{{ session('info') }}</span>
                </div>
                <button @click="show = false" class="text-sky-500 hover:text-sky-700">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main App Container: 3-Column Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Sidebar (Desktop Navigation) -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-24 space-y-4">
                <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-200/80 space-y-1">
                    <div class="px-3 py-2 mb-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menu Utama</span>
                    </div>

                    <!-- Feed: Nongkrong Yuk -->
                    <a href="{{ route('feed.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('feed.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>Nongkrong Yuk</span>
                    </a>

                    <!-- Sirkel: Satu Sirkel -->
                    <a href="{{ route('communities.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('communities.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Satu Sirkel</span>
                    </a>

                    <!-- Forum: Tongkrongan.id -->
                    <a href="{{ route('threads.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('threads.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Tongkrongan.id</span>
                    </a>

                    <!-- Teman Main: Quick Matchmaking -->
                    <a href="{{ route('matchmaking.index') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('matchmaking.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Teman Main</span>
                    </a>

                    <!-- Notifikasi -->
                    <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-3.5 py-3 rounded-2xl font-bold text-sm transition-all {{ request()->routeIs('notifications.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'text-slate-600 hover:bg-slate-100 hover:text-indigo-600' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span>Notifikasi</span>
                        </div>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-black {{ request()->routeIs('notifications.*') ? 'bg-white text-indigo-600' : 'bg-pink-500 text-white' }}">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Quick Action Button -->
                <div class="bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl p-5 text-white shadow-lg shadow-indigo-500/20 space-y-3">
                    <p class="font-extrabold text-base leading-tight">Buat Komunitas Sendiri!</p>
                    <p class="text-xs text-indigo-100 leading-relaxed">Kumpulin teman satu hobi dari berbagai sekolah dalam satu wadah seru.</p>
                    <a href="{{ route('communities.create') }}" class="inline-flex items-center justify-center w-full py-2.5 px-4 bg-white text-indigo-600 hover:bg-indigo-50 font-bold text-xs rounded-xl shadow transition-all">
                        + Bikin Sirkel Baru
                    </a>
                </div>
            </aside>

            <!-- Middle Workspace (Main Content) -->
            <main class="col-span-1 lg:col-span-6 space-y-6 pb-20 lg:pb-8">
                @yield('content')
            </main>

            <!-- Right Sidebar (Widgets Rail) -->
            <aside class="hidden lg:block lg:col-span-3 sticky top-24 space-y-6">
                
                <!-- Widget 1: Rekomendasi Teman Main -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-1.5">
                            <span>🎮</span> Rekomendasi Teman
                        </h3>
                        <a href="{{ route('matchmaking.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Semua</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($suggestedFriends ?? [] as $friend)
                            <div class="flex items-center justify-between gap-3 p-2 rounded-2xl hover:bg-slate-50 transition-colors">
                                <a href="{{ route('profile.show', $friend->username) }}" class="flex items-center gap-2.5 min-w-0">
                                    <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-200">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $friend->name }}</p>
                                        <p class="text-[11px] text-slate-400 truncate">{{ $friend->school ? $friend->school->city : 'Pelajar' }}</p>
                                    </div>
                                </a>
                                <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $friend->username]) }}" class="shrink-0 p-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all text-xs font-bold" title="Ajak Main">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                </a>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-2">Belum ada rekomendasi baru.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Widget 2: Komunitas Populer (Trending Sirkel) -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-1.5">
                            <span>🔥</span> Sirkel Terpopuler
                        </h3>
                        <a href="{{ route('communities.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Jelajah</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($trendingCommunities ?? [] as $comm)
                            <a href="{{ route('communities.show', $comm->slug) }}" class="flex items-center gap-3 p-2 rounded-2xl hover:bg-slate-50 transition-colors group">
                                <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-200">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition-colors truncate">{{ $comm->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $comm->members_count }} Anggota • {{ $comm->hobby ? $comm->hobby->name : 'Umum' }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-2">Belum ada sirkel terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Widget 3: Topik Forum Hangat -->
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200/80 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-1.5">
                            <span>💬</span> Topik Hangat
                        </h3>
                        <a href="{{ route('threads.index', ['sort' => 'trending']) }}" class="text-xs font-bold text-indigo-600 hover:underline">Forum</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($hotThreads ?? [] as $thread)
                            <a href="{{ route('threads.show', $thread->id) }}" class="block p-2.5 rounded-2xl hover:bg-slate-50 transition-colors group">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-indigo-50 text-indigo-600">
                                        #{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400">{{ $thread->comments_count }} balasan</span>
                                </div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-indigo-600 line-clamp-2 transition-colors">
                                    {{ $thread->title }}
                                </p>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-2">Belum ada utas aktif.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-lg px-4 py-2 flex items-center justify-around">
        <!-- Feed -->
        <a href="{{ route('feed.index') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('feed.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
            <span class="text-[10px]">Feed</span>
        </a>

        <!-- Sirkel -->
        <a href="{{ route('communities.index') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('communities.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="text-[10px]">Sirkel</span>
        </a>

        <!-- Central Action: Bikin Post / Thread -->
        <a href="{{ route('threads.create') }}" class="w-12 h-12 -mt-5 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30 hover:scale-105 transition-transform" title="Buat Utas Baru">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        </a>

        <!-- Forum -->
        <a href="{{ route('threads.index') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('threads.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            <span class="text-[10px]">Forum</span>
        </a>

        <!-- Teman Main -->
        <a href="{{ route('matchmaking.index') }}" class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl {{ request()->routeIs('matchmaking.*') ? 'text-indigo-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="text-[10px]">Teman</span>
        </a>
    </nav>

    <!-- Global Toast Script for Copy Link / Interaction -->
    <div id="toast-container" class="fixed bottom-20 lg:bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto px-4 py-3 rounded-2xl shadow-xl text-sm font-bold flex items-center gap-2 transform transition-all duration-300 translate-y-4 opacity-0 ${type === 'success' ? 'bg-slate-900 text-white' : 'bg-rose-600 text-white'}`;
            toast.innerHTML = `<span>✨</span> <span>${message}</span>`;
            container.appendChild(toast);
            
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            });
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function copyToClipboard(text, message = 'Tautan berhasil disalin ke clipboard!') {
            navigator.clipboard.writeText(text).then(() => {
                showToast(message);
            }).catch(() => {
                showToast('Gagal menyalin tautan.', 'error');
            });
        }
    </script>
</body>
</html>
