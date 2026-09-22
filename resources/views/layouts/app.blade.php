<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF8F5] text-slate-800 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sirkelku - Platform Komunitas & Teman Sefrekuensi Pelajar' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js for micro-interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex flex-col bg-[#FAF8F5] text-slate-800 selection:bg-orange-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation Header -->
    @include('layouts.navbar')

    <!-- Flash Messages (Clean Banners) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="p-3.5 mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        @if(session('info'))
            <div class="p-3.5 mb-4 rounded-xl bg-orange-50 border border-orange-200 text-orange-900 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-show="show">
                <div class="flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-orange-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                    <span>{{ session('info') }}</span>
                </div>
                <button @click="show = false" class="text-orange-400 hover:text-orange-600 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif
    </div>

    <!-- Main App Container: 3-Column Natural Sticky Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 w-full flex-1">
        <div class="dashboard-layout">
            
            <!-- Left Sidebar (Desktop Navigation) -->
            <aside class="sidebar-left hidden lg:block lg:col-span-3 space-y-4">
                <div class="sk-card-static p-4 space-y-1">
                    <div class="px-3 py-1.5 mb-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Menu Navigasi</span>
                    </div>

                    <!-- Feed: Nongkrong Yuk -->
                    <a href="{{ route('feed.index') }}" class="sk-nav-item {{ request()->routeIs('feed.*') ? 'sk-nav-active' : 'sk-nav-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>Nongkrong Yuk</span>
                    </a>

                    <!-- Sirkel: Satu Sirkel -->
                    <a href="{{ route('communities.index') }}" class="sk-nav-item {{ request()->routeIs('communities.*') ? 'sk-nav-active' : 'sk-nav-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Satu Sirkel</span>
                    </a>

                    <!-- Forum: Tongkrongan.id -->
                    <a href="{{ route('threads.index') }}" class="sk-nav-item {{ request()->routeIs('threads.*') ? 'sk-nav-active' : 'sk-nav-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span>Tongkrongan.id</span>
                    </a>

                    <!-- Teman Main: Quick Matchmaking -->
                    <a href="{{ route('matchmaking.index') }}" class="sk-nav-item {{ request()->routeIs('matchmaking.*') ? 'sk-nav-active' : 'sk-nav-inactive' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Teman Main</span>
                    </a>

                    <!-- Notifikasi -->
                    <a href="{{ route('notifications.index') }}" class="sk-nav-item justify-between {{ request()->routeIs('notifications.*') ? 'sk-nav-active' : 'sk-nav-inactive' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span>Notifikasi</span>
                        </div>
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold {{ request()->routeIs('notifications.*') ? 'bg-white text-orange-600' : 'bg-orange-500 text-white' }}">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Quick Action Box -->
                <div class="bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 rounded-2xl p-4.5 text-white shadow-md space-y-3">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-200 animate-pulse"></span>
                        <p class="font-extrabold text-sm leading-tight text-white">Buat Komunitas Sendiri</p>
                    </div>
                    <p class="text-xs text-orange-100 leading-relaxed font-medium">Kumpulkan teman satu hobi dari berbagai sekolah dalam satu sirkel.</p>
                    <a href="{{ route('communities.create') }}" class="inline-flex items-center justify-center w-full py-2.5 px-3 bg-white text-orange-600 hover:bg-orange-50 font-bold text-xs rounded-xl shadow-md hover:shadow-lg hover:scale-[1.02] transition-all transform duration-200">
                        + Bikin Sirkel Baru
                    </a>
                </div>
            </aside>

            <!-- Middle Workspace (Main Content) -->
            <main class="feed-column col-span-1 lg:col-span-6 space-y-5 pb-20 lg:pb-8">
                @yield('content')
            </main>

            <!-- Right Sidebar (Widgets Rail) -->
            <aside class="sidebar-right hidden lg:block lg:col-span-3 space-y-4">
                
                <!-- Widget 1: Rekomendasi Teman Main -->
                <div class="sk-card-static p-4.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-orange-100 pb-2.5">
                        <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                            <span class="text-orange-500">🎮</span> Rekomendasi Teman
                        </h3>
                        <a href="{{ route('matchmaking.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">Semua</a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($suggestedFriends ?? [] as $friend)
                            <div class="flex items-center justify-between gap-3 p-1.5 rounded-xl hover:bg-orange-50/60 transition-colors">
                                <a href="{{ route('profile.show', $friend->username) }}" class="flex items-center gap-2.5 min-w-0">
                                    <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-orange-200">
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate hover:text-orange-600 transition-colors">{{ $friend->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-medium truncate">{{ $friend->school ? $friend->school->city : 'Pelajar' }}</p>
                                    </div>
                                </a>
                                <a href="{{ route('matchmaking.index', ['tab' => 'discover', 'q' => $friend->username]) }}" class="shrink-0 p-1.5 rounded-lg border border-orange-200 hover:bg-orange-500 hover:text-white hover:border-orange-500 text-orange-600 transition-all text-xs" title="Ajak Main">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                </a>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-2 font-medium">Belum ada rekomendasi baru.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Widget 2: Komunitas Populer -->
                <div class="sk-card-static p-4.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-orange-100 pb-2.5">
                        <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                            <span class="text-amber-500">🔥</span> Sirkel Terpopuler
                        </h3>
                        <a href="{{ route('communities.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">Jelajah</a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($trendingCommunities ?? [] as $index => $comm)
                            <a href="{{ route('communities.show', $comm->slug) }}" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-orange-50/60 transition-colors group">
                                <span class="sk-rank-badge {{ $index == 0 ? 'sk-rank-1' : ($index == 1 ? 'sk-rank-2' : 'sk-rank-3') }}">
                                    {{ $index + 1 }}
                                </span>
                                <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-orange-200">
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs font-bold text-slate-800 group-hover:text-orange-600 transition-colors truncate">{{ $comm->name }}</p>
                                    <p class="text-[11px] text-slate-400 font-medium truncate">{{ $comm->members_count }} Anggota • {{ $comm->hobby ? $comm->hobby->name : 'Umum' }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-2 font-medium">Belum ada sirkel terdaftar.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Widget 3: Topik Forum Hangat -->
                <div class="sk-card-static p-4.5 space-y-3">
                    <div class="flex items-center justify-between border-b border-orange-100 pb-2.5">
                        <h3 class="font-extrabold text-xs uppercase tracking-wider text-slate-900 flex items-center gap-1.5">
                            <span class="text-teal-500">💬</span> Topik Hangat
                        </h3>
                        <a href="{{ route('threads.index', ['sort' => 'trending']) }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">Forum</a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse($hotThreads ?? [] as $thread)
                            <a href="{{ route('threads.show', $thread->id) }}" class="block p-2 rounded-xl hover:bg-orange-50/60 transition-colors group">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="sk-badge-orange">
                                        #{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-semibold">{{ $thread->comments_count }} balasan</span>
                                </div>
                                <p class="text-xs font-bold text-slate-800 group-hover:text-orange-600 line-clamp-2 transition-colors">
                                    {{ $thread->title }}
                                </p>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 text-center py-2 font-medium">Belum ada utas aktif.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-orange-100 shadow-md px-4 py-2 flex items-center justify-around">
        <!-- Feed -->
        <a href="{{ route('feed.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('feed.*') ? 'text-orange-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
            <span class="text-[10px]">Feed</span>
        </a>

        <!-- Sirkel -->
        <a href="{{ route('communities.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('communities.*') ? 'text-orange-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="text-[10px]">Sirkel</span>
        </a>

        <!-- Central Action: Bikin Post / Thread -->
        <a href="{{ route('threads.create') }}" class="w-10 h-10 -mt-4 rounded-full bg-orange-500 text-white flex items-center justify-center shadow-md hover:bg-orange-600 transition-colors" title="Buat Utas Baru">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        </a>

        <!-- Forum -->
        <a href="{{ route('threads.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('threads.*') ? 'text-orange-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            <span class="text-[10px]">Forum</span>
        </a>

        <!-- Teman Main -->
        <a href="{{ route('matchmaking.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('matchmaking.*') ? 'text-orange-600 font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="text-[10px]">Teman</span>
        </a>
    </nav>

    <!-- Global Toast Script -->
    <div id="toast-container" class="fixed bottom-20 lg:bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>
    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto px-4 py-3 rounded-xl shadow-md text-xs font-bold flex items-center gap-2 transform transition-all duration-200 translate-y-4 opacity-0 ${type === 'success' ? 'bg-slate-900 text-white border border-slate-700' : 'bg-rose-600 text-white'}`;
            toast.innerHTML = `<span>${message}</span>`;
            container.appendChild(toast);
            
            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-4', 'opacity-0');
            });
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function copyToClipboard(text, message = 'Tautan berhasil disalin!') {
            navigator.clipboard.writeText(text).then(() => {
                showToast(message);
            }).catch(() => {
                showToast('Gagal menyalin tautan.', 'error');
            });
        }
    </script>
</body>
</html>
