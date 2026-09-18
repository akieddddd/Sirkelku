@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Clean Minimalist Header (Flat Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-200 pb-4">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-950 tracking-tight">
                Forum Diskusi (Tongkrongan.id)
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500">
                Tanya-jawab masalah hobi, minta saran gear/settingan, atau obrolan santai antar pelajar.
            </p>
        </div>
        <a href="{{ route('threads.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shrink-0 shadow-sm">
            <span>+</span> Buat Utas Baru
        </a>
    </div>

    <!-- Sorting & Channels Bar -->
    <div class="bg-white rounded-xl p-3 shadow-sm border border-zinc-200 space-y-3">
        <!-- Sorting Tabs & Search -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1 bg-zinc-100 p-1 rounded-lg">
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" 
                    class="px-3 py-1.5 rounded-md text-xs transition-colors {{ (!request('sort') || request('sort') === 'latest') ? 'bg-white text-zinc-950 font-semibold shadow-xs' : 'text-zinc-600 hover:text-zinc-950 font-medium' }}">
                    Terbaru
                </a>
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'trending'])) }}" 
                    class="px-3 py-1.5 rounded-md text-xs transition-colors {{ request('sort') === 'trending' ? 'bg-white text-zinc-950 font-semibold shadow-xs' : 'text-zinc-600 hover:text-zinc-950 font-medium' }}">
                    Paling Ramai
                </a>
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'unanswered'])) }}" 
                    class="px-3 py-1.5 rounded-md text-xs transition-colors {{ request('sort') === 'unanswered' ? 'bg-white text-zinc-950 font-semibold shadow-xs' : 'text-zinc-600 hover:text-zinc-950 font-medium' }}">
                    Belum Terjawab
                </a>
            </div>

            <!-- Search input -->
            <form action="{{ route('threads.index') }}" method="GET" class="w-full sm:w-56">
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                @if(request('hobby')) <input type="hidden" name="hobby" value="{{ request('hobby') }}"> @endif
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik diskusi..."
                        class="w-full pl-8 pr-3 py-1.5 bg-zinc-50 text-xs rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400">
                    <svg class="w-3.5 h-3.5 text-zinc-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>

        <!-- Hobby Channels Pills Bar (Clean Horizontal Scroll) -->
        <div class="pt-2.5 border-t border-zinc-100 flex items-center gap-1.5 overflow-x-auto pb-0.5 no-scrollbar">
            <a href="{{ route('threads.index', array_merge(request()->except('hobby'))) }}"
                class="px-3 py-1 rounded-full text-xs font-medium shrink-0 transition-colors {{ !request('hobby') ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                Semua Hobi
            </a>
            @foreach($hobbies as $h)
                <a href="{{ route('threads.index', array_merge(request()->query(), ['hobby' => $h->id])) }}"
                    class="px-3 py-1 rounded-full text-xs font-medium shrink-0 transition-colors {{ request('hobby') == $h->id ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200' }}">
                    #{{ $h->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Threads List (Divide-y border-zinc-200) -->
    <div class="bg-white rounded-xl shadow-sm border border-zinc-200 divide-y divide-zinc-200 overflow-hidden">
        @forelse($threads as $thread)
            <div class="p-4 sm:p-5 transition-colors hover:bg-zinc-50/80 flex flex-col sm:flex-row items-start justify-between gap-4 group {{ $thread->is_pinned ? 'bg-amber-50/20' : '' }}">
                
                <div class="space-y-2 flex-1">
                    <!-- Badges -->
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($thread->is_pinned)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                Disematkan
                            </span>
                        @endif

                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-zinc-100 text-zinc-700 border border-zinc-200">
                            #{{ $thread->hobby ? $thread->hobby->name : 'Umum' }}
                        </span>

                        @if($thread->community)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-zinc-100 text-zinc-700 border border-zinc-200">
                                {{ $thread->community->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <a href="{{ route('threads.show', $thread->id) }}" class="block text-base font-semibold text-zinc-950 group-hover:text-blue-600 transition-colors leading-snug">
                        {{ $thread->title }}
                    </a>

                    <!-- Excerpt -->
                    <p class="text-xs text-zinc-600 line-clamp-2 leading-relaxed font-normal">
                        {{ Str::limit($thread->body, 140) }}
                    </p>

                    <!-- Author Info -->
                    <div class="flex items-center gap-2 pt-1 text-xs text-zinc-400">
                        <img src="{{ $thread->user->avatar_url }}" alt="{{ $thread->user->name }}" class="w-4 h-4 rounded-full object-cover">
                        <span class="font-medium text-zinc-700">{{ $thread->user->name }}</span>
                        <span>•</span>
                        <span>{{ $thread->user->school ? $thread->user->school->school_name : 'Pelajar' }}</span>
                        <span>•</span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Comments Counter Pill -->
                <div class="shrink-0 flex sm:flex-col items-center justify-center py-2 px-3 rounded-lg bg-zinc-50 border border-zinc-200 text-center min-w-[65px]">
                    <span class="text-sm font-bold text-zinc-900">{{ $thread->comments_count }}</span>
                    <span class="text-[10px] font-medium text-zinc-400">balasan</span>
                </div>

            </div>
        @empty
            <div class="p-10 text-center space-y-2">
                <h3 class="font-bold text-sm text-zinc-800">Belum Ada Utas Diskusi</h3>
                <p class="text-xs text-zinc-500 max-w-xs mx-auto">Mulai topik diskusi pertama di kanal ini bersama teman-teman.</p>
                <div class="pt-1">
                    <a href="{{ route('threads.create') }}" class="inline-block px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white font-semibold text-xs">
                        + Buat Utas Baru
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $threads->links() }}
    </div>

</div>
@endsection
