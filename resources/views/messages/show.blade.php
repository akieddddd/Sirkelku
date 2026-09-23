@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-sm border border-orange-100 flex flex-col h-[70vh]">
        <!-- Header -->
        <div class="p-4 border-b border-orange-100 bg-orange-50/30 flex items-center gap-4 rounded-t-3xl">
            <a href="{{ route('messages.index') }}" class="p-2 hover:bg-orange-100 rounded-full text-slate-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <a href="{{ route('profile.show', $user->username) }}" class="flex items-center gap-3 group">
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full ring-2 ring-orange-200 object-cover">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 group-hover:text-orange-600 transition-colors">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-500">@<span>{{ $user->username }}</span></p>
                </div>
            </a>
            <span id="realtime-indicator" class="ml-auto text-[10px] font-bold text-emerald-500 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-full hidden">● Live</span>
        </div>

        <!-- Chat History -->
        <div class="flex-1 p-4 overflow-y-auto bg-slate-50 flex flex-col gap-3" id="chat-container">
            @forelse($messages as $msg)
                @if($msg->sender_id === Auth::id())
                    <div class="flex justify-end" id="msg-{{ $msg->id }}">
                        <div class="bg-orange-500 text-white px-4 py-2 rounded-2xl rounded-tr-none max-w-[80%] shadow-sm">
                            <p class="text-sm whitespace-pre-wrap">{{ $msg->content }}</p>
                            <span class="text-[10px] text-orange-200 mt-1 block text-right">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @else
                    <div class="flex justify-start" id="msg-{{ $msg->id }}">
                        <div class="bg-white border border-orange-100 text-slate-700 px-4 py-2 rounded-2xl rounded-tl-none max-w-[80%] shadow-sm">
                            <p class="text-sm whitespace-pre-wrap">{{ $msg->content }}</p>
                            <span class="text-[10px] text-slate-400 mt-1 block">{{ $msg->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endif
            @empty
                <div class="text-center my-auto" id="empty-state">
                    <p class="text-sm text-slate-500">Kirim pesan pertama ke {{ $user->name }}!</p>
                </div>
            @endforelse
        </div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-orange-100 rounded-b-3xl">
            <form id="message-form" class="flex gap-2">
                @csrf
                <input type="text" id="message-input" placeholder="Tulis pesan..." required autocomplete="off"
                    class="flex-1 bg-slate-50 border border-orange-200 focus:border-orange-500 focus:ring-orange-500 rounded-xl text-sm px-4 py-2.5 outline-none focus:ring-1">
                <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white rounded-xl px-5 py-2.5 flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const authUserId = {{ Auth::id() }};
    const receiverUserId = {{ $user->id }};
    const storeUrl = "{{ route('messages.store', $user) }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const chatContainer = document.getElementById('chat-container');

    // Auto-scroll to bottom
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Render a message bubble
    function renderMessage(content, isSender, time, id = null) {
        const emptyState = document.getElementById('empty-state');
        if (emptyState) emptyState.remove();

        const wrapper = document.createElement('div');
        wrapper.className = isSender ? 'flex justify-end' : 'flex justify-start';
        if (id) wrapper.id = 'msg-' + id;

        if (isSender) {
            wrapper.innerHTML = `
                <div class="bg-orange-500 text-white px-4 py-2 rounded-2xl rounded-tr-none max-w-[80%] shadow-sm">
                    <p class="text-sm whitespace-pre-wrap">${content}</p>
                    <span class="text-[10px] text-orange-200 mt-1 block text-right">${time}</span>
                </div>`;
        } else {
            wrapper.innerHTML = `
                <div class="bg-white border border-orange-100 text-slate-700 px-4 py-2 rounded-2xl rounded-tl-none max-w-[80%] shadow-sm">
                    <p class="text-sm whitespace-pre-wrap">${content}</p>
                    <span class="text-[10px] text-slate-400 mt-1 block">${time}</span>
                </div>`;
        }

        chatContainer.appendChild(wrapper);
        scrollToBottom();
    }

    // Handle form submit via AJAX
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('message-input');
        const content = input.value.trim();
        if (!content) return;

        // Optimistic UI: render immediately
        const now = new Date();
        const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        renderMessage(content, true, time);
        input.value = '';
        input.focus();

        // Send to server
        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ content: content })
        });
    });

    // Listen for incoming messages via Laravel Echo
    if (typeof window.Echo !== 'undefined') {
        document.getElementById('realtime-indicator').classList.remove('hidden');

        window.Echo.private('chat.' + authUserId)
            .listen('MessageSent', (e) => {
                // Only show if it's from the person we're chatting with
                if (e.sender_id === receiverUserId) {
                    renderMessage(e.content, false, e.created_at, e.id);
                }
            });
    }

    scrollToBottom();
</script>
@endsection
