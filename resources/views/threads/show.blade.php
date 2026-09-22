@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Back to Forum -->
    <div class="flex items-center justify-between">
        <a href="{{ route('threads.index') }}" class="text-xs font-bold text-slate-500 hover:text-orange-600 flex items-center gap-1 transition-colors">
            &larr; Kembali ke Forum Tongkrongan
        </a>

        <!-- Pin Action Button if user has privilege -->
        @if($canPin)
            <form action="{{ route('threads.pin', $thread->id) }}" method="POST">
                @csrf
                <button type="submit" class="sk-btn-secondary text-xs py-1.5 px-3 flex items-center gap-1.5">
                    <span>{{ $thread->is_pinned ? 'Lepas Sematan' : '📌 Sematkan Utas' }}</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Main Thread Card -->
    <div class="sk-card p-5 sm:p-6 space-y-4 {{ $thread->is_pinned ? 'border-amber-300 bg-amber-50/20' : '' }}">
        <!-- Badges & Author -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.show', $thread->user->username) }}">
                    <img src="{{ $thread->user->avatar_url }}" alt="{{ $thread->user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-orange-200">
                </a>
                <div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('profile.show', $thread->user->username) }}" class="text-sm font-bold text-slate-900 hover:text-orange-600 transition-colors">
                            {{ $thread->user->name }}
                        </a>
                        <span class="text-xs text-orange-600 font-semibold">@<span>{{ $thread->user->username }}</span></span>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">
                        {{ $thread->user->school ? $thread->user->school->school_name : 'Pelajar' }} • {{ $thread->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($thread->is_pinned)
                    <span class="sk-badge-amber">
                        📌 Disematkan
                    </span>
                @endif
                <span class="sk-badge-orange">
                    #{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}
                </span>
                @if($thread->community)
                    <a href="{{ route('communities.show', $thread->community->slug) }}" class="sk-badge-teal hover:opacity-80">
                        Sirkel: {{ $thread->community->name }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Thread Title -->
        <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 leading-tight">
            {{ $thread->title }}
        </h1>

        <!-- Thread Body -->
        <div class="text-xs sm:text-sm text-slate-800 leading-relaxed whitespace-pre-line font-medium border-t border-orange-100 pt-3">
            {{ $thread->body }}
        </div>

        <!-- Meta bar -->
        <div class="pt-3 border-t border-orange-100 flex items-center justify-between text-xs text-slate-500 font-semibold">
            <span>{{ $thread->comments_count }} Balasan Diskusi</span>
            <button type="button" onclick="copyToClipboard(window.location.href, 'Tautan utas berhasil disalin!')" class="hover:text-orange-600 flex items-center gap-1 font-bold">
                Salin Tautan
            </button>
        </div>
    </div>

    <!-- Add Root Comment Form -->
    <div class="sk-card p-4 sm:p-5 space-y-3">
        <h3 class="font-extrabold text-xs text-slate-900 uppercase tracking-wider">
            Tulis Tanggapan
        </h3>
        <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-3">
            @csrf
            <textarea name="comment_text" rows="3" required placeholder="Tulis opini, jawaban, atau saran untuk topik ini..."
                class="sk-input text-xs sm:text-sm p-3 leading-relaxed"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="sk-btn-primary text-xs py-2 px-4">
                    Kirim Tanggapan
                </button>
            </div>
        </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900">
            Balasan & Diskusi ({{ $thread->comments_count }})
        </h3>

        @forelse($rootComments as $comment)
            <!-- Root Comment Card -->
            <div id="comment-{{ $comment->id }}" class="sk-card p-4 space-y-3" x-data="{ replyOpen: false }">
                <!-- Comment Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('profile.show', $comment->user->username) }}">
                            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-orange-200">
                        </a>
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.show', $comment->user->username) }}" class="text-xs font-bold text-slate-900 hover:text-orange-600 transition-colors">
                                    {{ $comment->user->name }}
                                </a>
                                @if($comment->user_id === $thread->user_id)
                                    <span class="sk-badge-orange text-[9px] py-0">Pembuat Utas</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 font-medium">
                                {{ $comment->user->school ? $comment->user->school->school_name : 'Pelajar' }} • {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <!-- Reply Trigger Button -->
                    <button @click="replyOpen = !replyOpen" class="text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors">
                        Balas
                    </button>
                </div>

                <!-- Comment Content -->
                <p class="text-xs sm:text-sm text-slate-800 leading-relaxed pl-10 font-medium">
                    {{ $comment->comment_text }}
                </p>

                <!-- Reply Input Form (Toggled) -->
                <div x-show="replyOpen" x-cloak class="pl-10 pt-2">
                    <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="comment_text" rows="2" required placeholder="Balas tanggapan {{ $comment->user->name }}..."
                            class="sk-input text-xs p-2.5"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="replyOpen = false" class="sk-btn-outline text-xs py-1.5 px-3">Batal</button>
                            <button type="submit" class="sk-btn-primary text-xs py-1.5 px-3.5">Kirim Balasan</button>
                        </div>
                    </form>
                </div>

                <!-- Nested Replies (Child Comments) -->
                @if($comment->replies->isNotEmpty())
                    <div class="pl-6 sm:pl-10 space-y-2.5 pt-2 border-l-2 border-orange-200 ml-4">
                        @foreach($comment->replies as $reply)
                            <div id="comment-{{ $reply->id }}" class="bg-orange-50/50 p-3 rounded-xl border border-orange-100/80 space-y-1.5" x-data="{ subReplyOpen: false }">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('profile.show', $reply->user->username) }}">
                                            <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-full object-cover ring-1 ring-orange-200">
                                        </a>
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('profile.show', $reply->user->username) }}" class="text-xs font-bold text-slate-900 hover:text-orange-600 transition-colors">
                                                {{ $reply->user->name }}
                                            </a>
                                            @if($reply->user_id === $thread->user_id)
                                                <span class="sk-badge-orange text-[8px] py-0">Author</span>
                                            @endif
                                            <span class="text-[10px] text-slate-400 font-medium">• {{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <button @click="subReplyOpen = !subReplyOpen" class="text-xs font-bold text-orange-600 hover:text-orange-700">
                                        Balas
                                    </button>
                                </div>

                                <p class="text-xs text-slate-800 leading-relaxed pl-8 font-medium">
                                    {{ $reply->comment_text }}
                                </p>

                                <!-- Sub reply form -->
                                <div x-show="subReplyOpen" x-cloak class="pl-8 pt-1">
                                    <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                        <input type="text" name="comment_text" required placeholder="Balas {{ $reply->user->name }}..."
                                            class="sk-input text-xs p-2">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" @click="subReplyOpen = false" class="sk-btn-outline text-xs py-1 px-2.5">Batal</button>
                                            <button type="submit" class="sk-btn-primary text-xs py-1 px-3">Kirim</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Deepest 3rd level replies if any -->
                                @if($reply->replies->isNotEmpty())
                                    <div class="pl-6 space-y-1.5 border-l border-orange-200">
                                        @foreach($reply->replies as $deepReply)
                                            <div class="p-2 bg-white rounded-lg border border-orange-100 text-xs">
                                                <span class="font-bold text-slate-900">{{ $deepReply->user->name }}</span>: 
                                                <span class="text-slate-700 font-medium">{{ $deepReply->comment_text }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @empty
            <div class="sk-card p-8 text-center text-slate-400 text-xs font-medium">
                Belum ada tanggapan pada utas ini. Tulis balasan pertamamu di atas!
            </div>
        @endforelse
    </div>

</div>
@endsection
