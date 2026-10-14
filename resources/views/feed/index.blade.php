@extends('layouts.app')

@section('content')
<div>
    <!-- Sticky Glassmorphic Header (Zephyr Style) -->
    <header class="sticky top-14 md:top-0 z-20 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-b border-slate-200 dark:border-zinc-800 px-2 sm:px-4 flex items-center justify-between gap-1 sm:gap-2 transition-colors">
        <!-- Tabs: Linimasa Utama, Sirkel Saya, Disukai, Disimpan -->
        <nav class="flex items-center gap-0.5 sm:gap-2 overflow-x-auto no-scrollbar shrink min-w-0">
            <a href="{{ route('feed.index', request('hobby') ? ['hobby' => request('hobby')] : []) }}" 
               class="relative py-3 px-2 sm:py-3.5 sm:px-3 text-xs sm:text-sm transition-all whitespace-nowrap {{ (!request('tab') || !in_array(request('tab'), ['my_circles', 'liked', 'saved'])) ? 'font-bold text-slate-900 dark:text-zinc-100' : 'font-semibold text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200' }}">
                <span>Linimasa Utama</span>
                @if(!request('tab') || !in_array(request('tab'), ['my_circles', 'liked', 'saved']))
                    <span class="absolute bottom-0 left-2 right-2 sm:left-3 sm:right-3 h-0.5 bg-[#FF4500] rounded-full"></span>
                @endif
            </a>

            @auth
                <a href="{{ route('feed.index', array_merge(['tab' => 'my_circles'], request('hobby') ? ['hobby' => request('hobby')] : [])) }}" 
                   class="relative py-3 px-2 sm:py-3.5 sm:px-3 text-xs sm:text-sm transition-all whitespace-nowrap {{ request('tab') === 'my_circles' ? 'font-bold text-slate-900 dark:text-zinc-100' : 'font-semibold text-slate-500 dark:text-zinc-400 hover:text-slate-800 dark:hover:text-zinc-200' }}">
                    <span>Sirkel Saya</span>
                    @if(request('tab') === 'my_circles')
                        <span class="absolute bottom-0 left-2 right-2 sm:left-3 sm:right-3 h-0.5 bg-[#FF4500] rounded-full"></span>
                    @endif
                </a>

                @if(request('tab') === 'liked')
                    <div class="relative py-3 px-2 sm:py-3.5 sm:px-3 text-xs sm:text-sm font-bold text-slate-900 dark:text-zinc-100 flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-rose-500" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        <span>Postingan Disukai</span>
                        <span class="absolute bottom-0 left-2 right-2 sm:left-3 sm:right-3 h-0.5 bg-[#FF4500] rounded-full"></span>
                    </div>
                @endif

                @if(request('tab') === 'saved')
                    <div class="relative py-3 px-2 sm:py-3.5 sm:px-3 text-xs sm:text-sm font-bold text-slate-900 dark:text-zinc-100 flex items-center gap-1.5 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-[#FF4500]" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        <span>Postingan Disimpan</span>
                        <span class="absolute bottom-0 left-2 right-2 sm:left-3 sm:right-3 h-0.5 bg-[#FF4500] rounded-full"></span>
                    </div>
                @endif
            @endauth
        </nav>

        <!-- Filter by Hobby Dropdown -->
        <div class="flex items-center gap-1 sm:gap-1.5 py-2 shrink-0">
            <form action="{{ route('feed.index') }}" method="GET" class="flex items-center gap-1 sm:gap-1.5">
                @if(request('tab')) 
                    <input type="hidden" name="tab" value="{{ request('tab') }}"> 
                @endif
                @if(request('saved_ids'))
                    <input type="hidden" name="saved_ids" value="{{ request('saved_ids') }}">
                @endif
                @if(request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                <select name="hobby" onchange="this.form.submit()" 
                    class="py-1 px-2 sm:px-2.5 text-xs font-semibold rounded-full bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200/70 dark:hover:bg-zinc-700 text-slate-700 dark:text-zinc-200 border border-transparent dark:border-zinc-700 focus:border-[#FF4500] focus:ring-1 focus:ring-[#FF4500] focus:bg-white dark:focus:bg-zinc-900 outline-none cursor-pointer transition-all max-w-[125px] sm:max-w-none truncate">
                    <option value="">Semua Kategori Hobi</option>
                    @foreach($hobbies as $h)
                        <option value="{{ $h->id }}" {{ request('hobby') == $h->id ? 'selected' : '' }}>
                            #{{ $h->name }}
                        </option>
                    @endforeach
                </select>
                @if(request('hobby'))
                    <a href="{{ route('feed.index', array_filter(['tab' => request('tab'), 'saved_ids' => request('saved_ids'), 'q' => request('q')])) }}" 
                       class="text-xs text-rose-500 hover:text-rose-700 font-bold px-1" title="Reset Filter">
                        &times;
                    </a>
                @endif
            </form>
        </div>
    </header>

    <!-- Post Composer (only on Linimasa Utama or Sirkel Saya) -->
    @if(!in_array(request('tab'), ['liked', 'saved']))
        <x-post-composer :hobbies="$hobbies" :userCommunities="$userCommunities" />
    @endif

    <!-- Continuous Posts List -->
    <div>
        @forelse($posts as $post)
            <x-post-card :post="$post" />
        @empty
            <div class="p-12 text-center space-y-2">
                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-400 dark:text-zinc-500 mb-3">
                    @if(request('tab') === 'liked')
                        <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                    @elseif(request('tab') === 'saved')
                        <svg class="w-6 h-6 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    @endif
                </div>

                @if(request('tab') === 'liked')
                    <h3 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Belum ada postingan disukai</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 max-w-xs mx-auto font-medium">Sukai postingan di linimasa untuk melihatnya di sini.</p>
                @elseif(request('tab') === 'saved')
                    <h3 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Belum ada postingan disimpan</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 max-w-xs mx-auto font-medium">Simpan postingan penting agar mudah ditemukan kembali.</p>
                @else
                    <h3 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Belum ada postingan</h3>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 max-w-xs mx-auto font-medium">Mulai bagikan cerita atau kegiatan hobimu.</p>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="p-4 border-t border-slate-100 dark:border-zinc-800 bg-white dark:bg-zinc-900 transition-colors">
        {{ $posts->links() }}
    </div>
</div>
@endsection
