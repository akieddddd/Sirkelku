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
        background: #FF4500;
    }
</style>

<div class="max-w-4xl mx-auto py-4 sm:py-6 px-3 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 flex flex-col h-[78vh] sm:h-[82vh] overflow-hidden relative">
        
        <!-- Header -->
        <div class="p-3.5 sm:p-4 border-b border-slate-200 bg-white flex items-center justify-between gap-3 shrink-0">
            <div class="flex items-center gap-3 min-w-0">
                <a href="{{ route('messages.index') }}" class="p-2 -ml-1 hover:bg-slate-100 rounded-full text-slate-500 hover:text-[#FF4500] transition-colors shrink-0" title="Kembali">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </a>
                
                <a href="{{ route('profile.show', $user->username) }}" class="flex items-center gap-3 group min-w-0">
                    <div class="relative shrink-0">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full ring-2 ring-slate-200 object-cover group-hover:scale-105 transition-transform duration-200">
                        <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-[#FF4500] ring-2 ring-white"></span>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm sm:text-base font-extrabold text-slate-800 group-hover:text-[#FF4500] transition-colors truncate">{{ $user->name }}</h2>
                        <div class="flex items-center gap-1.5 text-xs text-slate-500">
                            <span class="truncate">@<span>{{ $user->username }}</span></span>
                            @if($user->school)
                                <span class="hidden sm:inline-block text-[11px] text-[#FF4500] font-medium truncate">• {{ $user->school->school_name }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <!-- Sound Toggle -->
                <button type="button" id="sound-toggle-btn" class="p-2 rounded-xl text-slate-400 hover:text-[#FF4500] hover:bg-slate-50 transition-colors" title="Suara Notifikasi Chat">
                    <svg id="sound-icon-on" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                    </svg>
                    <svg id="sound-icon-off" class="w-4 h-4 hidden text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                    </svg>
                </button>

                <!-- Live Realtime Badge -->
                <span id="realtime-indicator" class="inline-flex items-center gap-1.5 text-[10px] sm:text-[11px] font-bold text-[#2D472C] bg-[#FFF0E6] border border-[#CDE0CD] px-2.5 py-1 rounded-full shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-[#FF4500] animate-pulse"></span>
                    <span>Live</span>
                </span>
            </div>
        </div>

        <!-- Chat History -->
        <div class="flex-1 p-3 sm:p-5 overflow-y-auto bg-[#F8FAFC] flex flex-col gap-2.5 sm:gap-3" id="chat-container">
            @forelse($messages as $msg)
                @if($msg->deleted_for_everyone)
                    @if($msg->sender_id === Auth::id())
                        <div class="flex items-center justify-end gap-1.5 group msg-row msg-animate" id="msg-{{ $msg->id }}" data-id="{{ $msg->id }}" data-sender="true" data-deleted-everyone="true">
                            <div class="opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150 shrink-0">
                                <button type="button" class="msg-menu-btn p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition-colors" data-id="{{ $msg->id }}" data-sender="true" data-deleted-everyone="true" title="Opsi pesan">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                </button>
                            </div>
                            <div class="msg-bubble bg-[#FFF0E6] border border-[#FFD8BF] text-slate-500 px-4 py-2.5 rounded-2xl rounded-tr-xs max-w-[85%] sm:max-w-[75%] shadow-2xs">
                                <div class="flex items-center gap-1.5 text-xs sm:text-sm italic text-slate-500 select-none">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6" /></svg>
                                    <span>Pesan ini telah dihapus</span>
                                </div>
                                <div class="flex items-center justify-end mt-1">
                                    <span class="device-time text-[10px] text-slate-400 font-medium" data-timestamp="{{ $msg->created_at->toIso8601String() }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-start gap-1.5 group msg-row msg-animate" id="msg-{{ $msg->id }}" data-id="{{ $msg->id }}" data-sender="false" data-deleted-everyone="true">
                            <div class="msg-bubble bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2.5 rounded-2xl rounded-tl-xs max-w-[85%] sm:max-w-[75%] shadow-2xs">
                                <div class="flex items-center gap-1.5 text-xs sm:text-sm italic text-slate-400 select-none">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6" /></svg>
                                    <span>Pesan ini telah dihapus</span>
                                </div>
                                <span class="device-time text-[10px] text-slate-400 font-medium mt-1 block" data-timestamp="{{ $msg->created_at->toIso8601String() }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150 shrink-0">
                                <button type="button" class="msg-menu-btn p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition-colors" data-id="{{ $msg->id }}" data-sender="false" data-deleted-everyone="true" title="Opsi pesan">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                </button>
                            </div>
                        </div>
                    @endif
                @else
                    @if($msg->sender_id === Auth::id())
                        <div class="flex items-center justify-end gap-1.5 group msg-row msg-animate" id="msg-{{ $msg->id }}" data-id="{{ $msg->id }}" data-sender="true" data-deleted-everyone="false">
                            <div class="opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150 shrink-0">
                                <button type="button" class="msg-menu-btn p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition-colors" data-id="{{ $msg->id }}" data-sender="true" data-deleted-everyone="false" title="Opsi pesan">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                </button>
                            </div>
                            <div class="msg-bubble bg-[#FF4500] text-white px-4 py-2.5 rounded-2xl rounded-tr-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                                <p class="msg-text text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">{{ $msg->content }}</p>
                                <div class="flex items-center justify-end gap-1.5 mt-1">
                                    <span class="device-time text-[10px] text-white/80 font-medium" data-timestamp="{{ $msg->created_at->toIso8601String() }}">
                                        {{ $msg->created_at->format('H:i') }}
                                    </span>
                                    @if($msg->read_at)
                                        {{-- Centang 2: Sudah Dibaca --}}
                                        <svg class="status-check is-read w-4 h-3.5 text-sky-200 shrink-0 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Dibaca">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M1.5 13l4 4L14.5 8M7 13l4 4L20 8" />
                                        </svg>
                                    @else
                                        {{-- Centang 1: Belum Dibaca --}}
                                        <svg class="status-check w-3.5 h-3.5 text-white/70 shrink-0 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Terkirim">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-start gap-1.5 group msg-row msg-animate" id="msg-{{ $msg->id }}" data-id="{{ $msg->id }}" data-sender="false" data-deleted-everyone="false">
                            <div class="msg-bubble bg-[#F1F5F9] border border-slate-200 text-[#1E293B] px-4 py-2.5 rounded-2xl rounded-tl-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                                <p class="msg-text text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">{{ $msg->content }}</p>
                                <span class="device-time text-[10px] text-slate-500 font-medium mt-1 block" data-timestamp="{{ $msg->created_at->toIso8601String() }}">
                                    {{ $msg->created_at->format('H:i') }}
                                </span>
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150 shrink-0">
                                <button type="button" class="msg-menu-btn p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition-colors" data-id="{{ $msg->id }}" data-sender="false" data-deleted-everyone="false" title="Opsi pesan">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                                </button>
                            </div>
                        </div>
                    @endif
                @endif
            @empty
                <div class="text-center my-auto p-8 space-y-2" id="empty-state">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Belum ada pesan</h3>
                    <p class="text-xs text-slate-500 max-w-xs mx-auto font-medium">Kirim pesan untuk memulai obrolan dengan {{ $user->name }}.</p>
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
                    class="flex-1 bg-white hover:bg-slate-50/50 focus:bg-white border border-slate-200 focus:border-[#FF4500] focus:ring-2 focus:ring-[#FF4500]/20 rounded-2xl text-xs sm:text-sm px-4 py-3 outline-none transition-all duration-150 text-slate-800">
                <button type="submit" id="send-button" class="bg-[#FF4500] hover:bg-[#E03E00] active:scale-95 text-white rounded-2xl px-4 sm:px-5 py-3 flex items-center justify-center shadow-xs transition-all duration-150 shrink-0" title="Kirim">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Floating Context Menu for Message Actions (Fixed, Collision-free) -->
<div id="chat-msg-menu" class="fixed hidden z-50 bg-white border border-slate-200 shadow-xl rounded-2xl py-1 w-48 text-xs font-semibold text-slate-700 ring-1 ring-black/5">
    <button type="button" id="menu-btn-everyone" class="w-full text-left px-3.5 py-2.5 hover:bg-rose-50 text-rose-600 flex items-center gap-2.5 transition-colors">
        <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <span>Hapus untuk semua</span>
    </button>
    <button type="button" id="menu-btn-me" class="w-full text-left px-3.5 py-2.5 hover:bg-slate-100 text-slate-700 flex items-center gap-2.5 transition-colors">
        <svg class="w-4 h-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <span>Hapus untuk saya</span>
    </button>
</div>

<!-- Modal Konfirmasi Hapus untuk Semua -->
<div id="delete-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs hidden transition-all duration-150">
    <div class="bg-white rounded-3xl max-w-sm w-full p-5 sm:p-6 shadow-2xl border border-slate-100 space-y-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>
        <div class="text-center space-y-1.5">
            <h3 class="text-base font-extrabold text-slate-800">Hapus pesan untuk semua?</h3>
            <p class="text-xs text-slate-500 leading-relaxed font-medium">
                Pesan ini akan diganti dengan status <span class="italic text-slate-600 font-semibold">"Pesan ini telah dihapus"</span> untuk Anda dan lawan bicara.
            </p>
        </div>
        <div class="flex items-center gap-2.5 pt-2">
            <button type="button" id="delete-modal-cancel" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 active:scale-95 transition-all text-center">
                Batal
            </button>
            <button type="button" id="delete-modal-confirm" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-95 transition-all shadow-xs text-center">
                Hapus untuk Semua
            </button>
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

    // SVGs for Read Receipts (WhatsApp style)
    const SINGLE_CHECK_SVG = `
        <svg class="status-check w-3.5 h-3.5 text-white/70 shrink-0 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Terkirim">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M5 13l4 4L19 7" />
        </svg>
    `;

    const DOUBLE_CHECK_SVG = `
        <svg class="status-check is-read w-4 h-3.5 text-sky-200 shrink-0 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Dibaca">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.3" d="M1.5 13l4 4L14.5 8M7 13l4 4L20 8" />
        </svg>
    `;

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

    // Gentle crystal chime using Web Audio API
    function playChatChime() {
        if (!soundEnabled) return;
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.type = 'sine';
            osc.frequency.setValueAtTime(659.25, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.1);
            
            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {
            // Audio policy fallback
        }
    }

    // Set of rendered message IDs for deduplication
    const renderedIds = new Set();
    document.querySelectorAll('.msg-row[data-id]').forEach(el => {
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

    // Check & display empty state if all messages are removed
    function checkEmptyState() {
        const rows = chatContainer.querySelectorAll('.msg-row');
        if (rows.length === 0 && !document.getElementById('empty-state')) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'text-center my-auto p-8 space-y-2';
            emptyDiv.id = 'empty-state';
            emptyDiv.innerHTML = `
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Belum ada pesan</h3>
                <p class="text-xs text-slate-500 max-w-xs mx-auto font-medium">Kirim pesan untuk memulai obrolan dengan {{ $user->name }}.</p>
            `;
            chatContainer.appendChild(emptyDiv);
        }
    }

    // Transform a message row into WhatsApp-style retracted bubble
    function markMessageAsDeletedForEveryone(row) {
        if (!row) return;
        row.setAttribute('data-deleted-everyone', 'true');
        const isSender = row.getAttribute('data-sender') === 'true';
        const timeEl = row.querySelector('.device-time');
        const timeString = timeEl ? timeEl.textContent : formatDeviceTime();

        const bubble = row.querySelector('.msg-bubble');
        if (bubble) {
            if (isSender) {
                bubble.className = 'msg-bubble bg-[#FFF0E6] border border-[#FFD8BF] text-slate-500 px-4 py-2.5 rounded-2xl rounded-tr-xs max-w-[85%] sm:max-w-[75%] shadow-2xs';
                bubble.innerHTML = `
                    <div class="flex items-center gap-1.5 text-xs sm:text-sm italic text-slate-500 select-none">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6" /></svg>
                        <span>Pesan ini telah dihapus</span>
                    </div>
                    <div class="flex items-center justify-end mt-1">
                        <span class="device-time text-[10px] text-slate-400 font-medium">${timeString}</span>
                    </div>
                `;
            } else {
                bubble.className = 'msg-bubble bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2.5 rounded-2xl rounded-tl-xs max-w-[85%] sm:max-w-[75%] shadow-2xs';
                bubble.innerHTML = `
                    <div class="flex items-center gap-1.5 text-xs sm:text-sm italic text-slate-400 select-none">
                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6" /></svg>
                        <span>Pesan ini telah dihapus</span>
                    </div>
                    <span class="device-time text-[10px] text-slate-400 font-medium mt-1 block">${timeString}</span>
                `;
            }
        }

        const menuBtn = row.querySelector('.msg-menu-btn');
        if (menuBtn) {
            menuBtn.setAttribute('data-deleted-everyone', 'true');
        }
    }

    // Render a message bubble smoothly
    function renderMessage(content, isSender, timestampIso, id = null, isOptimistic = false, isRead = false, isDeletedEveryone = false) {
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
        wrapper.className = `flex items-center ${isSender ? 'justify-end' : 'justify-start'} gap-1.5 group msg-row msg-animate`;
        if (id) {
            wrapper.id = 'msg-' + id;
            wrapper.setAttribute('data-id', id);
        } else if (isOptimistic) {
            wrapper.id = 'msg-optimistic-' + Date.now();
        }
        wrapper.setAttribute('data-sender', isSender ? 'true' : 'false');
        wrapper.setAttribute('data-deleted-everyone', isDeletedEveryone ? 'true' : 'false');

        const menuButtonHtml = `
            <div class="opacity-0 group-hover:opacity-100 focus-within:opacity-100 transition-opacity duration-150 shrink-0">
                <button type="button" class="msg-menu-btn p-1.5 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition-colors" data-id="${id || ''}" data-sender="${isSender ? 'true' : 'false'}" data-deleted-everyone="${isDeletedEveryone ? 'true' : 'false'}" title="Opsi pesan">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                </button>
            </div>
        `;

        if (isDeletedEveryone) {
            if (isSender) {
                wrapper.innerHTML = `
                    ${menuButtonHtml}
                    <div class="msg-bubble bg-[#FFF0E6] border border-[#FFD8BF] text-slate-500 px-4 py-2.5 rounded-2xl rounded-tr-xs max-w-[85%] sm:max-w-[75%] shadow-2xs">
                        <div class="flex items-center gap-1.5 text-xs sm:text-sm italic text-slate-500 select-none">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6" /></svg>
                            <span>Pesan ini telah dihapus</span>
                        </div>
                        <div class="flex items-center justify-end mt-1">
                            <span class="device-time text-[10px] text-slate-400 font-medium">${timeString}</span>
                        </div>
                    </div>
                `;
            } else {
                wrapper.innerHTML = `
                    <div class="msg-bubble bg-slate-100 border border-slate-200 text-slate-500 px-4 py-2.5 rounded-2xl rounded-tl-xs max-w-[85%] sm:max-w-[75%] shadow-2xs">
                        <div class="flex items-center gap-1.5 text-xs sm:text-sm italic text-slate-400 select-none">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2" /><path stroke-linecap="round" stroke-width="2" d="M6 18L18 6" /></svg>
                            <span>Pesan ini telah dihapus</span>
                        </div>
                        <span class="device-time text-[10px] text-slate-400 font-medium mt-1 block">${timeString}</span>
                    </div>
                    ${menuButtonHtml}
                `;
            }
        } else if (isSender) {
            wrapper.innerHTML = `
                ${menuButtonHtml}
                <div class="msg-bubble bg-[#FF4500] text-white px-4 py-2.5 rounded-2xl rounded-tr-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                    <p class="msg-text text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">${escapeHtml(content)}</p>
                    <div class="flex items-center justify-end gap-1.5 mt-1">
                        <span class="device-time text-[10px] text-white/80 font-medium" data-timestamp="${timestampIso || ''}">${timeString}</span>
                        ${isRead ? DOUBLE_CHECK_SVG : SINGLE_CHECK_SVG}
                    </div>
                </div>
            `;
        } else {
            wrapper.innerHTML = `
                <div class="msg-bubble bg-[#F1F5F9] border border-slate-200 text-[#1E293B] px-4 py-2.5 rounded-2xl rounded-tl-xs max-w-[85%] sm:max-w-[75%] shadow-xs">
                    <p class="msg-text text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">${escapeHtml(content)}</p>
                    <span class="device-time text-[10px] text-slate-500 font-medium mt-1 block" data-timestamp="${timestampIso || ''}">${timeString}</span>
                </div>
                ${menuButtonHtml}
            `;
        }

        const wasNear = isNearBottom();
        chatContainer.appendChild(wrapper);

        if (isSender || wasNear) {
            scrollToBottom(true);
        } else {
            scrollBottomBtn.classList.remove('hidden');
        }

        if (!isSender && !isDeletedEveryone) {
            playChatChime();
        }

        return wrapper;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // --- Floating Message Action Menu & Modals ---
    const msgMenu = document.getElementById('chat-msg-menu');
    const menuBtnEveryone = document.getElementById('menu-btn-everyone');
    const menuBtnMe = document.getElementById('menu-btn-me');
    const deleteModal = document.getElementById('delete-modal');
    const deleteModalCancel = document.getElementById('delete-modal-cancel');
    const deleteModalConfirm = document.getElementById('delete-modal-confirm');

    let activeMenuMsgId = null;
    let pendingEveryoneMsgId = null;

    function openMsgMenu(btn, msgId, isSender, isDeleted) {
        activeMenuMsgId = msgId;
        const btnRect = btn.getBoundingClientRect();

        // Only sender of an active message can delete for everyone
        if (isSender && !isDeleted) {
            menuBtnEveryone.classList.remove('hidden');
        } else {
            menuBtnEveryone.classList.add('hidden');
        }

        msgMenu.classList.remove('hidden');

        // Dynamic positioning with collision prevention
        const menuWidth = 192; // 12rem
        const menuHeight = msgMenu.offsetHeight || 90;

        let left = isSender ? (btnRect.left - menuWidth + 24) : btnRect.left;
        if (left < 10) left = 10;
        if (left + menuWidth > window.innerWidth - 10) {
            left = window.innerWidth - menuWidth - 10;
        }

        let top = btnRect.bottom + 6;
        if (top + menuHeight > window.innerHeight - 20) {
            top = btnRect.top - menuHeight - 6;
        }

        msgMenu.style.left = `${left}px`;
        msgMenu.style.top = `${top}px`;
    }

    function closeMsgMenu() {
        if (msgMenu) msgMenu.classList.add('hidden');
        activeMenuMsgId = null;
    }

    // Event delegation for 3-dots buttons inside chat container
    chatContainer.addEventListener('click', (e) => {
        const btn = e.target.closest('.msg-menu-btn');
        if (btn) {
            e.stopPropagation();
            const msgId = btn.getAttribute('data-id');
            if (!msgId) return;
            const isSender = btn.getAttribute('data-sender') === 'true';
            const isDeleted = btn.getAttribute('data-deleted-everyone') === 'true';
            openMsgMenu(btn, msgId, isSender, isDeleted);
        }
    });

    // Close menu when clicking outside or scrolling
    document.addEventListener('click', (e) => {
        if (!e.target.closest('#chat-msg-menu') && !e.target.closest('.msg-menu-btn')) {
            closeMsgMenu();
        }
    });

    chatContainer.addEventListener('scroll', () => {
        closeMsgMenu();
    });

    // Handle "Hapus untuk semua"
    menuBtnEveryone.addEventListener('click', () => {
        if (!activeMenuMsgId) return;
        pendingEveryoneMsgId = activeMenuMsgId;
        closeMsgMenu();
        deleteModal.classList.remove('hidden');
    });

    deleteModalCancel.addEventListener('click', () => {
        deleteModal.classList.add('hidden');
        pendingEveryoneMsgId = null;
    });

    deleteModalConfirm.addEventListener('click', async () => {
        if (!pendingEveryoneMsgId) return;
        const msgId = pendingEveryoneMsgId;
        deleteModal.classList.add('hidden');
        pendingEveryoneMsgId = null;
        await executeDelete(msgId, 'for_everyone');
    });

    // Handle "Hapus untuk saya"
    menuBtnMe.addEventListener('click', async () => {
        if (!activeMenuMsgId) return;
        const msgId = activeMenuMsgId;
        closeMsgMenu();
        await executeDelete(msgId, 'for_me');
    });

    // Execute message deletion request
    async function executeDelete(msgId, deleteType) {
        try {
            const response = await fetch(`/messages/${msgId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ delete_type: deleteType })
            });

            if (response.ok) {
                const result = await response.json();
                if (result.status === 'success') {
                    const row = document.getElementById('msg-' + msgId);
                    if (deleteType === 'for_me') {
                        if (row) {
                            row.style.transition = 'all 0.25s cubic-bezier(0.16, 1, 0.3, 1)';
                            row.style.opacity = '0';
                            row.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                row.remove();
                                renderedIds.delete(parseInt(msgId));
                                checkEmptyState();
                            }, 250);
                        }
                    } else if (deleteType === 'for_everyone') {
                        if (row) {
                            markMessageAsDeletedForEveryone(row);
                        }
                    }
                }
            }
        } catch (err) {
            console.error('Gagal menghapus pesan:', err);
        }
    }

    // Handle sending message via AJAX
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');

    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();
        if (!content) return;

        // Optimistic UI rendering immediately (starts as unread single check ✓)
        const optWrapper = renderMessage(content, true, new Date().toISOString(), null, true, false, false);
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
                        const btn = optWrapper.querySelector('.msg-menu-btn');
                        if (btn) btn.setAttribute('data-id', data.id);
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

                // 1. Process new incoming/outgoing messages
                if (result.status === 'success' && Array.isArray(result.messages)) {
                    result.messages.forEach(msg => {
                        if (!renderedIds.has(msg.id)) {
                            renderMessage(
                                msg.content,
                                msg.is_sender,
                                msg.created_at_iso,
                                msg.id,
                                false,
                                msg.is_read,
                                msg.deleted_for_everyone
                            );
                        }
                    });
                }

                // 2. Upgrade checkmarks from single (✓) to double (✓✓) when read
                if (Array.isArray(result.read_ids)) {
                    result.read_ids.forEach(id => {
                        const row = document.getElementById('msg-' + id);
                        if (row) {
                            const checkIcon = row.querySelector('.status-check');
                            if (checkIcon && !checkIcon.classList.contains('is-read')) {
                                checkIcon.outerHTML = DOUBLE_CHECK_SVG;
                            }
                        }
                    });
                }

                // 3. Update messages that got deleted for everyone (WhatsApp style)
                if (Array.isArray(result.deleted_for_everyone_ids)) {
                    result.deleted_for_everyone_ids.forEach(id => {
                        const row = document.getElementById('msg-' + id);
                        if (row && row.getAttribute('data-deleted-everyone') !== 'true') {
                            markMessageAsDeletedForEveryone(row);
                        }
                    });
                }
            }
        } catch (err) {
            // Polling error handling
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
