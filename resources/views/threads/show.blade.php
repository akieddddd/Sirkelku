@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Back to Forum -->
    <div class="flex items-center justify-between">
        <a href="{{ route('threads.index') }}" class="text-xs font-bold text-slate-500 hover:text-indigo-600 flex items-center gap-1">
            &larr; Kembali ke Forum Tongkrongan
        </a>

        <!-- Pin Action Button if user has privilege -->
        @if($canPin)
            <form action="{{ route('threads.pin', $thread->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 {{ $thread->is_pinned ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    <span>📌</span>
                    <span>{{ $thread->is_pinned ? 'Lepas Sematan (Unpin)' : 'Sematkan Utas (Pin)' }}</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Main Thread Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border {{ $thread->is_pinned ? 'border-amber-200 ring-1 ring-amber-200 bg-amber-50/10' : 'border-slate-200/80' }} space-y-5">
        <!-- Badges & Author -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.show', $thread->user->username) }}">
                    <img src="{{ $thread->user->avatar_url }}" alt="{{ $thread->user->name }}" class="w-12 h-12 rounded-2xl object-cover ring-2 ring-slate-100">
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('profile.show', $thread->user->username) }}" class="text-sm font-extrabold text-slate-900 hover:text-indigo-600">
                            {{ $thread->user->name }}
                        </a>
                        <span class="text-xs text-slate-400">@<span>{{ $thread->user->username }}</span></span>
                    </div>
                    <p class="text-[11px] text-slate-400">
                        {{ $thread->user->school ? $thread->user->school->school_name : 'Pelajar' }} • {{ $thread->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($thread->is_pinned)
                    <span class="px-2.5 py-1 rounded-xl bg-amber-100 text-amber-800 text-[10px] font-black border border-amber-200">
                        📌 Pinned
                    </span>
                @endif
                <span class="px-2.5 py-1 rounded-xl bg-indigo-50 text-indigo-600 text-[10px] font-bold border border-indigo-100">
                    #{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}
                </span>
                @if($thread->community)
                    <a href="{{ route('communities.show', $thread->community->slug) }}" class="px-2.5 py-1 rounded-xl bg-violet-50 text-violet-600 text-[10px] font-bold border border-violet-100 hover:bg-violet-100">
                        👥 {{ $thread->community->name }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Thread Title -->
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 leading-tight">
            {{ $thread->title }}
        </h1>

        <!-- Thread Body -->
        <div class="text-sm sm:text-base text-slate-800 leading-relaxed whitespace-pre-line font-normal border-t border-slate-100 pt-4">
            {{ $thread->body }}
        </div>

        <!-- Meta bar -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500 font-bold">
            <span>💬 {{ $thread->comments_count }} Balasan Diskusi</span>
            <button type="button" onclick="copyToClipboard(window.location.href, 'Tautan utas berhasil disalin!')" class="hover:text-indigo-600 flex items-center gap-1">
                🔗 Salin Tautan
            </button>
        </div>
    </div>

    <!-- Add Root Comment Form -->
    <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-200/80 space-y-3">
        <h3 class="font-extrabold text-sm text-slate-900 flex items-center gap-2">
            <span>✍️</span> Tulis Tanggapan atau Jawaban
        </h3>
        <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-3">
            @csrf
            <textarea name="comment_text" rows="3" required placeholder="Tulis opini, jawaban, atau tips yang membantu untuk topik ini..."
                class="w-full p-3.5 bg-slate-50 focus:bg-white text-xs sm:text-sm rounded-2xl border border-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 resize-none"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-600/20 transition-all">
                    Kirim Tanggapan Utama
                </button>
            </div>
        </form>
    </div>

    <!-- Nested Comments List -->
    <div class="space-y-4">
        <h3 class="font-extrabold text-base text-slate-900">
            Balasan & Diskusi ({{ $thread->comments_count }})
        </h3>

        @forelse($rootComments as $comment)
            <!-- Root Comment Card -->
            <div id="comment-{{ $comment->id }}" class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200/80 space-y-4" x-data="{ replyOpen: false }">
                <!-- Comment Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('profile.show', $comment->user->username) }}">
                            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200">
                        </a>
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.show', $comment->user->username) }}" class="text-xs font-bold text-slate-900 hover:underline">
                                    {{ $comment->user->name }}
                                </a>
                                @if($comment->user_id === $thread->user_id)
                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-indigo-100 text-indigo-700">Pembuat Utas</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400">
                                {{ $comment->user->school ? $comment->user->school->school_name : 'Pelajar' }} • {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <!-- Reply Trigger Button -->
                    <button @click="replyOpen = !replyOpen" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                        <span>↩️</span> Balas
                    </button>
                </div>

                <!-- Comment Content -->
                <p class="text-xs sm:text-sm text-slate-800 leading-relaxed pl-10 font-normal">
                    {{ $comment->comment_text }}
                </p>

                <!-- Reply Input Form (Toggled) -->
                <div x-show="replyOpen" x-cloak class="pl-10 pt-2">
                    <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="comment_text" rows="2" required placeholder="Balas tanggapan {{ $comment->user->name }}..."
                            class="w-full p-3 bg-slate-50 focus:bg-white text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="replyOpen = false" class="px-3 py-1.5 text-xs text-slate-500 hover:bg-slate-100 rounded-xl">Batal</button>
                            <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold">Kirim Balasan</button>
                        </div>
                    </form>
                </div>

                <!-- Nested Replies (Child Comments) -->
                @if($comment->replies->isNotEmpty())
                    <div class="pl-6 sm:pl-10 space-y-3 pt-2 border-l-2 border-indigo-100 ml-4">
                        @foreach($comment->replies as $reply)
                            <div id="comment-{{ $reply->id }}" class="bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100 space-y-2" x-data="{ subReplyOpen: false }">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('profile.show', $reply->user->username) }}">
                                            <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-lg object-cover">
                                        </a>
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('profile.show', $reply->user->username) }}" class="text-xs font-bold text-slate-900 hover:underline">
                                                {{ $reply->user->name }}
                                            </a>
                                            @if($reply->user_id === $thread->user_id)
                                                <span class="px-1.5 py-0.5 rounded text-[8px] font-black bg-indigo-100 text-indigo-700">Author</span>
                                            @endif
                                            <span class="text-[10px] text-slate-400">• {{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <button @click="subReplyOpen = !subReplyOpen" class="text-[11px] font-bold text-indigo-600 hover:underline">
                                        Balas
                                    </button>
                                </div>

                                <p class="text-xs text-slate-700 leading-relaxed pl-8">
                                    {{ $reply->comment_text }}
                                </p>

                                <!-- Sub reply form -->
                                <div x-show="subReplyOpen" x-cloak class="pl-8 pt-1">
                                    <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                        <input type="text" name="comment_text" required placeholder="Balas {{ $reply->user->name }}..."
                                            class="w-full p-2.5 bg-white text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" @click="subReplyOpen = false" class="px-2.5 py-1 text-xs text-slate-400">Batal</button>
                                            <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-lg text-xs font-bold">Kirim</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Deepest 3rd level replies if any -->
                                @if($reply->replies->isNotEmpty())
                                    <div class="pl-6 space-y-2 border-l border-slate-200">
                                        @foreach($reply->replies as $deepReply)
                                            <div class="p-2 bg-white rounded-xl border border-slate-100 text-xs">
                                                <span class="font-bold text-slate-900">{{ $deepReply->user->name }}</span>: 
                                                <span class="text-slate-700">{{ $deepReply->comment_text }}</span>
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
            <div class="bg-white rounded-3xl p-8 text-center text-slate-400 text-xs border border-slate-200/80">
                Belum ada tanggapan pada utas ini. Tulis balasan pertamamu di atas!
            </div>
        @endforelse
    </div>

</div>
@endsection
