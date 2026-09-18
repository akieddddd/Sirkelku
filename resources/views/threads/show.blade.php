@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Back to Forum -->
    <div class="flex items-center justify-between">
        <a href="{{ route('threads.index') }}" class="text-xs font-medium text-zinc-500 hover:text-zinc-950 flex items-center gap-1">
            &larr; Kembali ke Forum Tongkrongan
        </a>

        <!-- Pin Action Button if user has privilege -->
        @if($canPin)
            <form action="{{ route('threads.pin', $thread->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors flex items-center gap-1.5 {{ $thread->is_pinned ? 'bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200' }}">
                    <span>{{ $thread->is_pinned ? 'Lepas Sematan' : 'Sematkan Utas' }}</span>
                </button>
            </form>
        @endif
    </div>

    <!-- Main Thread Card -->
    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm border border-zinc-200 space-y-4 {{ $thread->is_pinned ? 'border-amber-200 bg-amber-50/10' : '' }}">
        <!-- Badges & Author -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('profile.show', $thread->user->username) }}">
                    <img src="{{ $thread->user->avatar_url }}" alt="{{ $thread->user->name }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-zinc-200">
                </a>
                <div>
                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('profile.show', $thread->user->username) }}" class="text-sm font-semibold text-zinc-950 hover:text-blue-600">
                            {{ $thread->user->name }}
                        </a>
                        <span class="text-xs text-zinc-400">@<span>{{ $thread->user->username }}</span></span>
                    </div>
                    <p class="text-xs text-zinc-500">
                        {{ $thread->user->school ? $thread->user->school->school_name : 'Pelajar' }} • {{ $thread->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                @if($thread->is_pinned)
                    <span class="px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-800 text-[11px] font-semibold border border-amber-200">
                        Disematkan
                    </span>
                @endif
                <span class="px-2.5 py-0.5 rounded-full bg-zinc-100 text-zinc-700 text-[11px] font-medium border border-zinc-200">
                    #{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}
                </span>
                @if($thread->community)
                    <a href="{{ route('communities.show', $thread->community->slug) }}" class="px-2.5 py-0.5 rounded-full bg-zinc-100 text-zinc-700 text-[11px] font-medium border border-zinc-200 hover:bg-zinc-200">
                        {{ $thread->community->name }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Thread Title -->
        <h1 class="text-lg sm:text-xl font-bold text-zinc-950 leading-tight">
            {{ $thread->title }}
        </h1>

        <!-- Thread Body -->
        <div class="text-xs sm:text-sm text-zinc-800 leading-relaxed whitespace-pre-line font-normal border-t border-zinc-100 pt-3">
            {{ $thread->body }}
        </div>

        <!-- Meta bar -->
        <div class="pt-3 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500 font-medium">
            <span>{{ $thread->comments_count }} Balasan Diskusi</span>
            <button type="button" onclick="copyToClipboard(window.location.href, 'Tautan utas berhasil disalin!')" class="hover:text-zinc-900 flex items-center gap-1">
                Salin Tautan
            </button>
        </div>
    </div>

    <!-- Add Root Comment Form -->
    <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-zinc-200 space-y-3">
        <h3 class="font-semibold text-xs text-zinc-900 uppercase tracking-wider">
            Tulis Tanggapan
        </h3>
        <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-3">
            @csrf
            <textarea name="comment_text" rows="3" required placeholder="Tulis opini, jawaban, atau saran untuk topik ini..."
                class="w-full p-3 bg-zinc-50 focus:bg-white text-xs sm:text-sm rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 resize-none leading-relaxed"></textarea>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shadow-sm">
                    Kirim Tanggapan
                </button>
            </div>
        </form>
    </div>

    <!-- Comments List -->
    <div class="space-y-3">
        <h3 class="font-bold text-sm text-zinc-950">
            Balasan & Diskusi ({{ $thread->comments_count }})
        </h3>

        @forelse($rootComments as $comment)
            <!-- Root Comment Card -->
            <div id="comment-{{ $comment->id }}" class="bg-white rounded-xl p-4 shadow-sm border border-zinc-200 space-y-3" x-data="{ replyOpen: false }">
                <!-- Comment Header -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <a href="{{ route('profile.show', $comment->user->username) }}">
                            <img src="{{ $comment->user->avatar_url }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full object-cover ring-1 ring-zinc-200">
                        </a>
                        <div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('profile.show', $comment->user->username) }}" class="text-xs font-semibold text-zinc-900 hover:underline">
                                    {{ $comment->user->name }}
                                </a>
                                @if($comment->user_id === $thread->user_id)
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-semibold bg-zinc-100 text-zinc-700 border border-zinc-200">Pembuat Utas</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-zinc-400">
                                {{ $comment->user->school ? $comment->user->school->school_name : 'Pelajar' }} • {{ $comment->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <!-- Reply Trigger Button -->
                    <button @click="replyOpen = !replyOpen" class="text-xs font-semibold text-zinc-600 hover:text-zinc-950 transition-colors">
                        Balas
                    </button>
                </div>

                <!-- Comment Content -->
                <p class="text-xs sm:text-sm text-zinc-700 leading-relaxed pl-10 font-normal">
                    {{ $comment->comment_text }}
                </p>

                <!-- Reply Input Form (Toggled) -->
                <div x-show="replyOpen" x-cloak class="pl-10 pt-2">
                    <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="comment_text" rows="2" required placeholder="Balas tanggapan {{ $comment->user->name }}..."
                            class="w-full p-2.5 bg-zinc-50 focus:bg-white text-xs rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="replyOpen = false" class="px-3 py-1.5 text-xs text-zinc-500 hover:bg-zinc-100 rounded-lg">Batal</button>
                            <button type="submit" class="px-3.5 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-semibold">Kirim Balasan</button>
                        </div>
                    </form>
                </div>

                <!-- Nested Replies (Child Comments) -->
                @if($comment->replies->isNotEmpty())
                    <div class="pl-6 sm:pl-10 space-y-2.5 pt-2 border-l-2 border-zinc-200 ml-4">
                        @foreach($comment->replies as $reply)
                            <div id="comment-{{ $reply->id }}" class="bg-zinc-50 p-3 rounded-lg border border-zinc-200 space-y-1.5" x-data="{ subReplyOpen: false }">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('profile.show', $reply->user->username) }}">
                                            <img src="{{ $reply->user->avatar_url }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-full object-cover">
                                        </a>
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('profile.show', $reply->user->username) }}" class="text-xs font-semibold text-zinc-900 hover:underline">
                                                {{ $reply->user->name }}
                                            </a>
                                            @if($reply->user_id === $thread->user_id)
                                                <span class="px-1 py-0.2 rounded text-[8px] font-semibold bg-zinc-200 text-zinc-700">Author</span>
                                            @endif
                                            <span class="text-[10px] text-zinc-400">• {{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <button @click="subReplyOpen = !subReplyOpen" class="text-xs font-semibold text-zinc-500 hover:text-zinc-900">
                                        Balas
                                    </button>
                                </div>

                                <p class="text-xs text-zinc-700 leading-relaxed pl-8">
                                    {{ $reply->comment_text }}
                                </p>

                                <!-- Sub reply form -->
                                <div x-show="subReplyOpen" x-cloak class="pl-8 pt-1">
                                    <form action="{{ route('threads.comment', $thread->id) }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="parent_id" value="{{ $reply->id }}">
                                        <input type="text" name="comment_text" required placeholder="Balas {{ $reply->user->name }}..."
                                            class="w-full p-2 bg-white text-xs rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400">
                                        <div class="flex justify-end gap-1.5">
                                            <button type="button" @click="subReplyOpen = false" class="px-2.5 py-1 text-xs text-zinc-400">Batal</button>
                                            <button type="submit" class="px-3 py-1 bg-zinc-900 text-white rounded-lg text-xs font-semibold">Kirim</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Deepest 3rd level replies if any -->
                                @if($reply->replies->isNotEmpty())
                                    <div class="pl-6 space-y-1.5 border-l border-zinc-300">
                                        @foreach($reply->replies as $deepReply)
                                            <div class="p-2 bg-white rounded-md border border-zinc-200 text-xs">
                                                <span class="font-semibold text-zinc-900">{{ $deepReply->user->name }}</span>: 
                                                <span class="text-zinc-700">{{ $deepReply->comment_text }}</span>
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
            <div class="bg-white rounded-xl p-8 text-center text-zinc-400 text-xs border border-zinc-200">
                Belum ada tanggapan pada utas ini. Tulis balasan pertamamu di atas!
            </div>
        @endforelse
    </div>

</div>
@endsection
