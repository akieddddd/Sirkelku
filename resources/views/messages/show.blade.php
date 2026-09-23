@extends('layouts.app')

@section('content')
<style>
    @keyframes bubbleEnter {
        0% {
            opacity: 0;
            transform: translateY(10px) scale(0.96);
        }
        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    .msg-animate {
        animation: bubbleEnter 0.28s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    /* Custom subtle scrollbar */
    #chat-container::-webkit-scrollbar {
        width: 6px;
    }
    #chat-container::-webkit-scrollbar-track {
        background: transparent;
    }
    #chat-container::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 9999px;
    }
    #chat-container::-webkit-scrollbar-thumb:hover {
        background: #588157;
    }
</style>

<div class="max-w-4xl mx-auto py-4 sm:py-6 px-3 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 flex flex-col h-[78vh] sm:h-[82vh] overflow-hidden relative">
        
        <!-- Header -->
        <div class="p-3.5 sm:p-4 border-b border-slate-200 bg-white flex items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('messages.index') }}" class="p-2 -ml-1 hover:bg-slate-100 rounded-full text-slate-500 hover:text-[#588157] transition-colors shrink-0" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                
                <a href="{{ route('profile.show', $user->username) }}" class="flex items-center gap-3 group min-w-0">
                    <div class="relative shrink-0">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full ring-2 ring-slate-200 object-cover group-hover:scale-105 transition-transform duration-200">
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-[#588157] ring-2 ring-white"></span>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base font-extrabold text-slate-800 group-hover:text-[#588157] transition-colors truncate">{{ $user->name }}</h2>
                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span class="truncate">@<span>{{ $user->username }}</span></span>
                            @if($user->school)
                                <span class="hidden sm:inline-block text-[11px] text-[#588157] font-medium truncate">• {{ $user->school->school_name }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <!-- Sound Toggle -->
                <button type="button" id="sound-toggle-btn" class="p-2 rounded-xl text-slate-400 hover:text-[#588157] hover:bg-slate-50 transition-colors" title="Suara Notifikasi Chat">
                    <svg id="sound-icon-on" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                    </svg>
                    <svg id="sound-icon-off" class="w-4 h-4 hidden text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                    </svg>
                </button>

                <!-- Live Realtime Badge -->
                <span id="realtime-indicator" class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold text-[#2D472C] bg-[#EAF0EA] border border-[#CDE0CD] px-2.5 py-1 rounded-full shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-[#588157] animate-pulse"></span>
                    <span>Live</span>
                </span>
            </div>
        </div>

        <!-- Chat History -->
        <div class="flex-1 p-3 sm:p-5 overflow-y-auto bg-[#F8FAFC] flex flex-col gap-2.5 sm:gap-3" id="chat-container">
            @forelse($messages as $msg)
                @if($msg->sender_id === Auth::id())
                    <div class="flex justify-end msg-animate" id="msg-{{ $msg->id }}" data-id="{{ $msg->id }}">
                        <div class="bg-[#588157] text-white px-4 py-2.5 rounded-2xl rounded-tr-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                            <p class="text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">{{ $msg->content }}</p>
                            <div class="flex items-center justify-end gap-1.5 mt-1">
                                <span class="device-time text-[10px] text-white/80 font-medium" data-timestamp="{{ $msg->created_at->toIso8601String() }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                                <svg class="w-3.5 h-3.5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="{{ $msg->read_at ? 'Dibaca' : 'Terkirim' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex justify-start msg-animate" id="msg-{{ $msg->id }}" data-id="{{ $msg->id }}">
                        <div class="bg-[#F1F5F9] border border-slate-200 text-[#1E293B] px-4 py-2.5 rounded-2xl rounded-tl-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                            <p class="text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">{{ $msg->content }}</p>
                            <span class="device-time text-[10px] text-slate-500 font-medium mt-1 block" data-timestamp="{{ $msg->created_at->toIso8601String() }}">
                                {{ $msg->created_at->format('H:i') }}
                            </span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center my-auto p-8" id="empty-state">
                    <div class="w-14 h-14 rounded-2xl bg-[#EAF0EA] border border-[#CDE0CD] flex items-center justify-center mx-auto mb-3 text-[#588157]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-800 mb-1">Mulai Obrolan dengan {{ $user->name }}</h3>
                    <p class="text-xs text-slate-500 max-w-xs mx-auto">Sapa, ajak mabar atau diskusi seputar sirkel kalian sekarang!</p>
                </div>
            @endforelse
        </div>

        <!-- Floating Scroll to Bottom / New Message Badge -->
        <button type="button" id="scroll-bottom-btn" class="hidden absolute bottom-20 right-6 bg-[#588157] hover:bg-[#476A46] text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1.5 transition-all duration-200 z-10">
            <span>Pesan Baru</span>
            <svg class="w-3.5 h-3.5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
        </button>

        <!-- Input Area -->
        <div class="p-3 sm:p-4 bg-white border-t border-slate-200 rounded-b-3xl shrink-0">
            <form id="message-form" class="flex items-center gap-2">
                @csrf
                <input type="text" id="message-input" placeholder="Ketik pesan..." required autocomplete="off"
                    class="flex-1 bg-white hover:bg-slate-50/50 focus:bg-white border border-slate-200 focus:border-[#588157] focus:ring-2 focus:ring-[#588157]/20 rounded-2xl text-xs sm:text-sm px-4 py-3 outline-none transition-all duration-150 text-slate-800">
                <button type="submit" id="send-button" class="bg-[#588157] hover:bg-[#476A46] active:scale-95 text-white rounded-2xl px-4 sm:px-5 py-3 flex items-center justify-center shadow-xs transition-all duration-150 shrink-0" title="Kirim">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const authUserId = {{ Auth::id() }};
    const receiverUserId = {{ $user->id }};
    const receiverUsername = "{{ $user->username }}";
    const storeUrl = "{{ route('messages.store', $user) }}";
    const syncUrl = "{{ route('messages.sync', $user->username) }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const chatContainer = document.getElementById('chat-container');
    const scrollBottomBtn = document.getElementById('scroll-bottom-btn');
    const soundToggleBtn = document.getElementById('sound-toggle-btn');
    const soundIconOn = document.getElementById('sound-icon-on');
    const soundIconOff = document.getElementById('sound-icon-off');

    // Sound configuration stored in localStorage
    let soundEnabled = localStorage.getItem('sirkelku_chat_sound') !== 'false';
    updateSoundIcon();

    soundToggleBtn.addEventListener('click', () => {
        soundEnabled = !soundEnabled;
        localStorage.setItem('sirkelku_chat_sound', soundEnabled);
        updateSoundIcon();
    });

    function updateSoundIcon() {
        if (soundEnabled) {
            soundIconOn.classList.remove('hidden');
            soundIconOff.classList.add('hidden');
        } else {
            soundIconOn.classList.add('hidden');
            soundIconOff.classList.remove('hidden');
        }
    }

    // Gentle crystal chime using Web Audio API (smooth & zero dependency)
    function playChatChime() {
        if (!soundEnabled) return;
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.type = 'sine';
            // Smooth two-tone chord: 659.25Hz (E5) gliding to 880Hz (A5)
            osc.frequency.setValueAtTime(659.25, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.1);
            
            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {
            // Audio context policy
        }
    }

    // Set of rendered message IDs for deduplication
    const renderedIds = new Set();
    document.querySelectorAll('[data-id]').forEach(el => {
        renderedIds.add(parseInt(el.getAttribute('data-id')));
    });

    // Highest known message ID for efficient sync polling
    let lastMessageId = 0;
    renderedIds.forEach(id => {
        if (id > lastMessageId) lastMessageId = id;
    });

    // Format timestamps according to the user's device settings & locale
    const deviceTimeFormatter = new Intl.DateTimeFormat(navigator.language || 'id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });

    function formatDeviceTime(isoString) {
        if (!isoString) {
            return deviceTimeFormatter.format(new Date());
        }
        try {
            return deviceTimeFormatter.format(new Date(isoString));
        } catch (e) {
            return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    }

    // Initial pass to convert all server-rendered timestamps to device local time
    function initDeviceTimestamps() {
        document.querySelectorAll('.device-time').forEach(el => {
            const iso = el.getAttribute('data-timestamp');
            if (iso) {
                el.textContent = formatDeviceTime(iso);
            }
        });
    }
    initDeviceTimestamps();

    // Auto-scroll to bottom
    function scrollToBottom(smooth = true) {
        if (smooth) {
            chatContainer.scrollTo({
                top: chatContainer.scrollHeight,
                behavior: 'smooth'
            });
        } else {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
        scrollBottomBtn.classList.add('hidden');
    }

    // Check if user is scrolled near bottom
    function isNearBottom() {
        return chatContainer.scrollHeight - chatContainer.scrollTop - chatContainer.clientHeight < 120;
    }

    chatContainer.addEventListener('scroll', () => {
        if (isNearBottom()) {
            scrollBottomBtn.classList.add('hidden');
        }
    });

    scrollBottomBtn.addEventListener('click', () => {
        scrollToBottom(true);
    });

    // Render a message bubble smoothly
    function renderMessage(content, isSender, timestampIso, id = null, isOptimistic = false) {
        if (id && renderedIds.has(id)) {
            return;
        }
        if (id) {
            renderedIds.add(id);
            if (id > lastMessageId) lastMessageId = id;
        }

        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.remove();

        const timeString = formatDeviceTime(timestampIso);
        const wrapper = document.createElement('div');
        wrapper.className = (isSender ? 'flex justify-end' : 'flex justify-start') + ' msg-animate';
        if (id) {
            wrapper.id = 'msg-' + id;
            wrapper.setAttribute('data-id', id);
        } else if (isOptimistic) {
            wrapper.id = 'msg-optimistic-' + Date.now();
        }

        if (isSender) {
            wrapper.innerHTML = `
                <div class="bg-[#588157] text-white px-4 py-2.5 rounded-2xl rounded-tr-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                    <p class="text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">${escapeHtml(content)}</p>
                    <div class="flex items-center justify-end gap-1.5 mt-1">
                        <span class="text-[10px] text-white/80 font-medium">${timeString}</span>
                        <svg class="w-3.5 h-3.5 text-white/80 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>`;
        } else {
            wrapper.innerHTML = `
                <div class="bg-[#F1F5F9] border border-slate-200 text-[#1E293B] px-4 py-2.5 rounded-2xl rounded-tl-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                    <p class="text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">${escapeHtml(content)}</p>
                    <span class="text-[10px] text-slate-500 font-medium mt-1 block">${timeString}</span>
                </div>`;
        }

        const wasNear = isNearBottom();
        chatContainer.appendChild(wrapper);

        if (isSender || wasNear) {
            scrollToBottom(true);
        } else {
            scrollBottomBtn.classList.remove('hidden');
        }

        if (!isSender) {
            playChatChime();
        }

        return wrapper;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Handle sending message via AJAX
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();
        if (!content) return;

        // Optimistic UI rendering immediately
        const optWrapper = renderMessage(content, true, new Date().toISOString(), null, true);
        messageInput.value = '';
        messageInput.focus();

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ content: content })
            });

            if (response.ok) {
                const data = await response.json();
                if (data && data.id) {
                    renderedIds.add(data.id);
                    if (data.id > lastMessageId) lastMessageId = data.id;
                    if (optWrapper) {
                        optWrapper.id = 'msg-' + data.id;
                        optWrapper.setAttribute('data-id', data.id);
                    }
                }
            }
        } catch (err) {
            console.error('Gagal mengirim pesan:', err);
        }
    });

    // Real-Time Message Synchronization Engine
    async function syncMessages() {
        try {
            const url = `${syncUrl}?after_id=${lastMessageId}`;
            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const result = await response.json();
                if (result.status === 'success' && Array.isArray(result.messages)) {
                    result.messages.forEach(msg => {
                        if (!renderedIds.has(msg.id)) {
                            renderMessage(
                                msg.content,
                                msg.is_sender,
                                msg.created_at_iso,
                                msg.id
                            );
                        }
                    });
                }
            }
        } catch (err) {
            // Network issue, continue next tick
        }
    }

    // Poll every 1.5 seconds when active, 3.5 seconds when window blurred
    let syncIntervalTime = 1500;
    let syncTimer = setInterval(syncMessages, syncIntervalTime);

    window.addEventListener('focus', () => {
        clearInterval(syncTimer);
        syncIntervalTime = 1500;
        syncMessages();
        syncTimer = setInterval(syncMessages, syncIntervalTime);
    });

    window.addEventListener('blur', () => {
        clearInterval(syncTimer);
        syncIntervalTime = 3500;
        syncTimer = setInterval(syncMessages, syncIntervalTime);
    });

    // Also listen to Laravel Echo if WebSocket / Reverb is connected
    if (typeof window.Echo !== 'undefined') {
        try {
            window.Echo.private('chat.' + authUserId)
                .listen('MessageSent', (e) => {
                    if (e.sender_id === receiverUserId) {
                        renderMessage(e.content, false, e.created_at_iso || new Date().toISOString(), e.id);
                    }
                });
        } catch (e) {
            // Echo fallback to polling
        }
    }

    // Initial scroll to bottom
    scrollToBottom(false);
</script>
@endsection
