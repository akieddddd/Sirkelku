@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#588157] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                Forum Diskusi (Tongkrongan.id)
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 font-medium">
                Tanya-jawab masalah hobi, minta saran gear/settingan, atau obrolan santai antar pelajar.
            </p>
        </div>
        <a href="{{ route('threads.create') }}" class="sk-btn-primary text-xs shrink-0">
            + Buat Utas Baru
        </a>
    </div>

    <!-- Sorting & Channels Bar -->
    <div class="sk-card p-4 space-y-3">
        <!-- Sorting Tabs & Search -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-1 bg-slate-100/80 p-1 rounded-xl border border-slate-200">
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all {{ (!request('sort') || request('sort') === 'latest') ? 'bg-[#588157] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-[#588157] font-semibold' }}">
                    Terbaru
                </a>
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'trending'])) }}" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all {{ request('sort') === 'trending' ? 'bg-[#588157] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-[#588157] font-semibold' }}">
                    Paling Ramai
                </a>
                <a href="{{ route('threads.index', array_merge(request()->query(), ['sort' => 'unanswered'])) }}" 
                    class="px-3 py-1.5 rounded-lg text-xs transition-all {{ request('sort') === 'unanswered' ? 'bg-[#588157] text-white font-bold shadow-xs' : 'text-slate-600 hover:text-[#588157] font-semibold' }}">
                    Belum Terjawab
                </a>
            </div>

            <!-- Search input -->
            <form action="{{ route('threads.index') }}" method="GET" class="w-full sm:w-64">
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                @if(request('hobby')) <input type="hidden" name="hobby" value="{{ request('hobby') }}"> @endif
                <div class="relative flex items-center">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari topik diskusi..."
                        class="sk-input pl-9 text-xs py-2 w-full">
                </div>
            </form>
        </div>

        <!-- Hobby Channels Pills Bar -->
        <div class="pt-2.5 border-t border-slate-100 flex items-center gap-1.5 overflow-x-auto pb-0.5 no-scrollbar">
            <a href="{{ route('threads.index', array_merge(request()->except('hobby'))) }}"
                class="px-3 py-1 rounded-full text-xs font-bold shrink-0 transition-all {{ !request('hobby') ? 'bg-[#588157] text-white shadow-xs' : 'bg-[#F8FAFC] border border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                Semua Hobi
            </a>
            @foreach($hobbies as $h)
                <a href="{{ route('threads.index', array_merge(request()->query(), ['hobby' => $h->id])) }}"
                    class="px-3 py-1 rounded-full text-xs font-bold shrink-0 transition-all {{ request('hobby') == $h->id ? 'bg-[#588157] text-white shadow-xs' : 'bg-[#F8FAFC] border border-slate-200 text-slate-700 hover:bg-slate-100' }}">
                    #{{ $h->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Threads List -->
    <div class="sk-card divide-y divide-slate-100 overflow-hidden">
        @forelse($threads as $thread)
            <div class="p-4 sm:p-5 transition-colors hover:bg-slate-50 flex flex-col sm:flex-row items-start justify-between gap-4 group {{ $thread->is_pinned ? 'bg-[#EAF0EA]/30' : '' }}">
                
                <div class="space-y-2 flex-1">
                    <!-- Badges -->
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($thread->is_pinned)
                            <span class="sk-badge-sage flex items-center gap-1">
                                <svg class="w-3 h-3 text-[#2D472C]" fill="currentColor" viewBox="0 0 24 24"><path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/></svg>
                                Disematkan
                            </span>
                        @endif

                        <span class="sk-badge-sage">
                            #{{ $thread->hobby ? $thread->hobby->name : 'Umum' }}
                        </span>

                        @if($thread->community)
                            <span class="sk-badge-muted">
                                Sirkel: {{ $thread->community->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Title -->
                    <a href="{{ route('threads.show', $thread->id) }}" class="block text-base font-bold text-slate-800 group-hover:text-[#588157] transition-colors leading-snug">
                        {{ $thread->title }}
                    </a>

                    <!-- Excerpt -->
                    <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed font-medium">
                        {{ Str::limit($thread->body, 140) }}
                    </p>

                    <!-- Author Info -->
                    <div class="flex items-center gap-2 pt-1 text-xs text-slate-400 font-medium">
                        <img src="{{ $thread->user->avatar_url }}" alt="{{ $thread->user->name }}" class="w-4 h-4 rounded-full object-cover">
                        <span class="font-bold text-slate-700 hover:text-[#588157] transition-colors">{{ $thread->user->name }}</span>
                        <span>•</span>
                        <span>{{ $thread->user->school ? $thread->user->school->school_name : 'Pelajar' }}</span>
                        <span>•</span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Comments Counter Pill -->
                <div class="shrink-0 flex sm:flex-col items-center justify-center py-2 px-3.5 rounded-xl bg-[#EAF0EA] border border-[#CDE0CD] text-center min-w-[70px]">
                    <span class="text-base font-extrabold text-[#2D472C]">{{ $thread->comments_count }}</span>
                    <span class="text-[10px] font-bold text-[#3D5A3C]">balasan</span>
                </div>

            </div>
        @empty
            <div class="p-10 text-center space-y-2">
                <h3 class="font-bold text-sm text-slate-800">Belum Ada Utas Diskusi</h3>
                <p class="text-xs text-slate-500 max-w-xs mx-auto font-medium">Mulai topik diskusi pertama di kanal ini bersama teman-teman.</p>
                <div class="pt-2">
                    <a href="{{ route('threads.create') }}" class="sk-btn-primary text-xs">
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
