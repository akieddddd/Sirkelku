<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8FAFC] text-slate-800 antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sirkelku - Platform Komunitas & Teman Sefrekuensi Pelajar' }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js for micro-interactions -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <!-- Dark Mode Anti-Flicker Script (PRD Section 5) -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen overflow-hidden bg-[#F8FAFC] dark:bg-zinc-950 text-slate-800 dark:text-zinc-100 selection:bg-[#FF4500] selection:text-white font-sans transition-colors" x-data="{ mobileDrawerOpen: false }" x-init="$watch('mobileDrawerOpen', val => document.body.classList.toggle('overflow-hidden', val))">

    <!-- Top Navbar Component (Desktop & Tablet) -->
    <x-top-navbar />

    <!-- 3-Column Social Feed Layout Container (Independent Scroll Architecture) -->
    <div class="h-screen md:h-[calc(100vh-4rem)] max-h-screen md:max-h-[calc(100vh-4rem)] overflow-hidden bg-[#F8FAFC] dark:bg-zinc-950">
        <div class="max-w-7xl mx-auto h-full flex justify-center overflow-hidden">
            
            <!-- Left Column: Navigation Sidebar (Independent Scroll) -->
            <x-sidebar-left />

            <!-- Middle Column: Continuous Timeline & Main Workspace (Independent Scroll) -->
            <main class="w-full min-w-0 md:max-w-2xl md:border-x border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 h-full overflow-y-auto custom-scrollbar flex-1 pb-20 md:pb-8 transition-colors">
                
                <!-- Mobile Top Header (< md) -->
                <x-mobile-header />

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="m-4 p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.duration.500ms>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 font-bold text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="m-4 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.duration.500ms>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                            <span>{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700 font-bold text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="m-4 p-3.5 rounded-2xl bg-slate-100 border border-slate-200 text-slate-800 text-xs sm:text-sm font-semibold flex items-center justify-between shadow-xs" x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition.duration.500ms>
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-slate-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                            <span>{{ session('info') }}</span>
                        </div>
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600 font-bold text-lg leading-none">&times;</button>
                    </div>
                @endif

                <!-- Page Content Slot -->
                @yield('content')
            </main>

            <!-- Right Column: Fixed Widgets Rail -->
            <x-sidebar-right />

        </div>
    </div>

    <!-- Mobile Navigation Drawer (< md) -->
    <x-mobile-drawer />

    <!-- Auth Wall Modal for Guest Interactions -->
    <x-auth-wall-modal />

    <!-- Mobile Bottom Navigation Bar (< md) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-md border-t border-slate-200 dark:border-zinc-800 shadow-sm px-4 py-2 flex items-center justify-around select-none transition-colors">
        <!-- Feed -->
        <a href="{{ route('feed.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('feed.*') ? 'text-[#FF4500] font-bold' : 'text-slate-400 dark:text-zinc-500 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
            <span class="text-[10px]">Feed</span>
        </a>

        <!-- Sirkel -->
        <a href="{{ route('communities.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('communities.*') ? 'text-[#FF4500] font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            <span class="text-[10px]">Sirkel</span>
        </a>

        <!-- Central Action: Bikin Sirkel Baru -->
        <a href="{{ route('communities.create') }}" class="w-11 h-11 -mt-5 rounded-full bg-[#FF4500] text-white flex items-center justify-center shadow-lg hover:bg-[#E03E00] transition-colors ring-4 ring-white" title="Sirkel Baru">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        </a>

        <!-- Forum -->
        <a href="{{ route('threads.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('threads.*') ? 'text-[#FF4500] font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
            <span class="text-[10px]">Forum</span>
        </a>

        <!-- Teman Main -->
        <a href="{{ route('matchmaking.index') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('matchmaking.*') ? 'text-[#FF4500] font-bold' : 'text-slate-400 font-medium' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="text-[10px]">Teman</span>
        </a>
    </nav>

    <!-- Global In-App Notifications & Toast Container -->
    <div id="toast-container" class="fixed bottom-20 lg:bottom-6 right-6 z-50 flex flex-col gap-2 pointer-events-none"></div>
    <div id="incoming-notification-container" class="fixed top-6 right-4 sm:right-6 z-50 flex flex-col gap-3 pointer-events-none max-w-sm w-full"></div>

    <script>
        // Global Simple Toast
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `pointer-events-auto px-4 py-3 rounded-2xl shadow-lg text-xs font-bold flex items-center gap-2 transform transition-all duration-300 translate-y-4 opacity-0 ${type === 'success' ? 'bg-slate-900 text-white border border-slate-700' : 'bg-rose-600 text-white'}`;
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

        function copyToClipboard(text, message = 'Tautan berhasil disalin') {
            navigator.clipboard.writeText(text).then(() => {
                showToast(message);
            }).catch(() => {
                showToast('Gagal menyalin tautan.', 'error');
            });
        }

        @auth
        (function() {
            const checkIncomingUrl = "{{ route('messages.checkIncoming') }}";
            let lastNotifiedMsgId = 0;
            const seenMessageIds = new Set();

            // Smooth Web Audio API chime
            function playGlobalChime() {
                try {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContext) return;
                    const ctx = new AudioContext();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    
                    osc.type = 'sine';
                    // Pleasant two-tone chime (587.33Hz D5 -> 880Hz A5)
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.12);
                    
                    gain.gain.setValueAtTime(0.15, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.38);
                    
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    
                    osc.start();
                    osc.stop(ctx.currentTime + 0.38);
                } catch(e) {}
            }

            // Format timestamp according to user's device settings & locale
            const deviceTimeFormatter = new Intl.DateTimeFormat(navigator.language || 'id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            // Smooth In-App Notification Card for Incoming Messages
            function showInAppMessageNotification(msg) {
                const container = document.getElementById('incoming-notification-container');
                if (!container) return;

                const card = document.createElement('div');
                card.className = "pointer-events-auto bg-white/95 backdrop-blur-md border border-slate-200 shadow-xl rounded-2xl p-3.5 flex items-start gap-3 transform transition-all duration-300 -translate-y-4 opacity-0 hover:shadow-2xl relative overflow-hidden group";
                
                const timeStr = msg.created_at_iso ? deviceTimeFormatter.format(new Date(msg.created_at_iso)) : deviceTimeFormatter.format(new Date());

                card.innerHTML = `
                    <div class="relative shrink-0 mt-0.5">
                        <img src="${msg.sender_avatar}" alt="${msg.sender_name}" class="w-10 h-10 rounded-full object-cover ring-2 ring-emerald-300">
                        <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-[#FF4500] rounded-full ring-2 ring-white flex items-center justify-center text-white p-0.5">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </span>
                    </div>
                    <div class="flex-1 min-w-0 pr-6">
                        <div class="flex items-baseline justify-between gap-1 mb-0.5">
                            <h4 class="text-xs font-black text-slate-800 truncate">${msg.sender_name}</h4>
                            <span class="text-[10px] font-medium text-slate-400 shrink-0">${timeStr}</span>
                        </div>
                        <p class="text-xs text-slate-600 line-clamp-2 leading-snug mb-2">${msg.content}</p>
                        <a href="${msg.chat_url}" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-white bg-[#FF4500] hover:bg-[#E03E00] px-2.5 py-1 rounded-xl transition-colors">
                            <span>Balas Pesan</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    <button type="button" class="close-btn absolute top-2 right-2 p-1 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <!-- Progress Bar Timer -->
                    <div class="absolute bottom-0 left-0 h-0.5 bg-[#FF4500] transition-all duration-[6000ms] ease-linear w-full progress-bar"></div>
                `;

                container.appendChild(card);

                // Entrance animation
                requestAnimationFrame(() => {
                    card.classList.remove('-translate-y-4', 'opacity-0');
                    const bar = card.querySelector('.progress-bar');
                    if (bar) bar.style.width = '0%';
                });

                // Dismiss action
                const dismiss = () => {
                    card.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => card.remove(), 300);
                };

                card.querySelector('.close-btn').addEventListener('click', dismiss);
                const timer = setTimeout(dismiss, 6000);

                // Play audio chime
                playGlobalChime();

                // Trigger Device / Browser Native Notification if allowed
                if ('Notification' in window && Notification.permission === 'granted') {
                    try {
                        const nativeNotif = new Notification(`${msg.sender_name} (Sirkelku)`, {
                            body: msg.content,
                            icon: msg.sender_avatar,
                        });
                        nativeNotif.onclick = () => {
                            window.focus();
                            window.location.href = msg.chat_url;
                        };
                    } catch(e) {}
                }
            }

            // Update badges in navigation (Sidebar & Mobile)
            function updateNavBadges(unreadMessages, unreadNotifications) {
                document.querySelectorAll('.nav-msg-badge, #nav-msg-badge').forEach(badge => {
                    if (unreadMessages > 0) {
                        badge.textContent = unreadMessages > 9 ? '9+' : unreadMessages;
                        badge.style.setProperty('display', '', '');
                        badge.classList.remove('hidden');
                    } else {
                        badge.style.setProperty('display', 'none', 'important');
                        badge.classList.add('hidden');
                    }
                });

                document.querySelectorAll('.nav-notif-badge, #nav-notif-badge').forEach(badge => {
                    if (unreadNotifications > 0) {
                        badge.textContent = unreadNotifications > 9 ? '9+' : unreadNotifications;
                        badge.style.setProperty('display', '', '');
                        badge.classList.remove('hidden');
                    } else {
                        badge.style.setProperty('display', 'none', 'important');
                        badge.classList.add('hidden');
                    }
                });
            }

            // Check incoming messages & notifications periodically
            async function checkIncoming() {
                try {
                    const response = await fetch(`${checkIncomingUrl}?since_id=${lastNotifiedMsgId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        updateNavBadges(data.unread_messages_count, data.unread_notifications_count);

                        if (Array.isArray(data.messages) && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                if (!seenMessageIds.has(msg.id)) {
                                    seenMessageIds.add(msg.id);
                                    if (msg.id > lastNotifiedMsgId) {
                                        lastNotifiedMsgId = msg.id;
                                    }

                                    // Don't pop up notification if user is already chatting with this specific sender
                                    const currentPath = window.location.pathname;
                                    const isCurrentChat = currentPath.includes('/messages/' + msg.sender_username);
                                    
                                    if (!isCurrentChat) {
                                        showInAppMessageNotification(msg);
                                    }
                                }
                            });
                        }
                    }
                } catch(e) {}
            }

            // Request browser notification permission politely on first user interaction if default
            if ('Notification' in window && Notification.permission === 'default') {
                window.addEventListener('click', function requestPermOnce() {
                    Notification.requestPermission();
                    window.removeEventListener('click', requestPermOnce);
                }, { once: true });
            }

            // Poll every 3.5s when active, 7s when blurred
            let pollTimer = setInterval(checkIncoming, 3500);

            window.addEventListener('focus', () => {
                clearInterval(pollTimer);
                checkIncoming();
                pollTimer = setInterval(checkIncoming, 3500);
            });

            window.addEventListener('blur', () => {
                clearInterval(pollTimer);
                pollTimer = setInterval(checkIncoming, 7000);
            });

            // Initial check
            setTimeout(checkIncoming, 1500);
        })();
        @endauth
    </script>
</body>
</html>
