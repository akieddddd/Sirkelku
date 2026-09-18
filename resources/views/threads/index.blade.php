@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-violet-600 via-indigo-600 to-purple-700 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-indigo-500/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="space-y-2 max-w-lg">
            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[11px] font-extrabold uppercase tracking-wider text-white">
                Forum Diskusi Remaja
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Tongkrongan.id</h1>
            <p class="text-xs sm:text-sm text-indigo-100 leading-relaxed">
                Tanya-jawab masalah hobi, minta saran gear/settingan, curhat santai, atau bahas topik seru bareng teman se-Indonesia.
            </p>
        </div>
        <a href="{{ route('threads.create') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white text-indigo-600 font-extrabold text-xs shadow-md hover:bg-indigo-50 hover:scale-105 active:scale-95 transition-all shrink-0">
            <span>+</span> Buat Utas Baru
        </a>
    </div>

    <!-- Sorting & Search Controls -->
    <div class="bg-white rounded-3xl p-4 shadow-sm border border-slate-200/80 space-y-3">
        <!-- Sorting Tabs -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1.5 sm:gap-2">
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ (!request('sort') || request('sort') === 'latest') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    ⚡ Terbaru
                </a>
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'trending'])) }}" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ request('sort') === 'trending' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    🔥 Paling Ramai
                </a>
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'unanswered'])) }}" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-extrabold transition-all {{ request('sort') === 'unanswered' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    ❓ Belum Terjawab
                </a>
            </div>

            <!-- Search input -->
            <form action="{{ route('threads.index') }}" method="GET" class="w-full sm:w-60">
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                @if(request('hobby')) <input type="hidden" name="hobby" value="{{ request('hobby') }}"> @endif
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik diskusi..."
                        class="w-full pl-8 pr-3 py-1.5 bg-slate-50 text-xs rounded-xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>

        <!-- Hobby Channels Pills Bar -->
        <div class="pt-2 border-t border-slate-100 flex items-center gap-2 overflow-x-auto pb-1 no-scrollbar">
            <a href="{{ route('threads.index', array_merge(request()->except('hobby'))) }}"
                class="px-3 py-1 rounded-xl text-xs font-bold shrink-0 transition-colors {{ !request('hobby') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua Hobi
            </a>
            @foreach($hobbies as $h)
                <a href="{{ route('threads.index', array_merge(request()->query(), ['hobby' => $h->id])) }}"
                    class="px-3 py-1 rounded-xl text-xs font-bold shrink-0 transition-colors {{ request('hobby') == $h->id ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    #{{ $h->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Threads List (15 per page) -->
    <div class="space-y-3">
        @forelse($threads as $thread)
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border {{ $thread->is_pinned ? 'border-amber-200 bg-amber-50/20 ring-1 ring-amber-200/50' : 'border-slate-200/80 hover:border-slate-300' }} transition-all flex flex-col sm:flex-row items-start justify-between gap-4 group">
                
                <div class="space-y-2 flex-1">
                    <!-- Badges -->
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($thread->is_pinned)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[10px] font-black bg-amber-100 text-amber-800 border border-amber-200">
                                📌 Disematkan (Pinned)
                            </span>
                        @endif

                        <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                            #{{ $thread->hobby ? $thread->hobby->name : 'Umum' }}
                        </span>

                        @if($thread->community)
                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-violet-50 text-violet-600 border border-violet-100">
                                👥 {{ $thread->community->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <a href="{{ route('threads.show', $thread->id) }}" class="block text-base font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors leading-snug">
                        {{ $thread->title }}
                    </a>

                    <!-- Excerpt -->
                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed font-normal">
                        {{ Str::limit($thread->body, 140) }}
                    </p>

                    <!-- Author Info -->
                    <div class="flex items-center gap-2 pt-1 text-[11px] text-slate-400">
                        <img src="{{ $thread->user->avatar_url }}" alt="{{ $thread->user->name }}" class="w-5 h-5 rounded-lg object-cover">
                        <span class="font-bold text-slate-700">{{ $thread->user->name }}</span>
                        <span>•</span>
                        <span>{{ $thread->user->school ? $thread->user->school->school_name : 'Pelajar' }}</span>
                        <span>•</span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Comments Counter Pill -->
                <div class="shrink-0 flex sm:flex-col items-center justify-center p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center min-w-[70px]">
                    <span class="text-base font-black text-indigo-600">{{ $thread->comments_count }}</span>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Balasan</span>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl mx-auto">
                    💬
                </div>
                <h3 class="font-extrabold text-base text-slate-800">Belum Ada Utas Diskusi</h3>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Mulai topik diskusi pertama di kanal ini dan diskusikan bersama teman-teman!</p>
                <a href="{{ route('threads.create') }}" class="inline-block mt-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow">
                    + Buat Utas Baru
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $threads->links() }}
    </div>

</div>
@endsection
