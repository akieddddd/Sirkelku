@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto p-4 sm:p-5">

    <div class="sk-card space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-3">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg sm:text-xl font-black text-slate-900 dark:text-zinc-100">
                        Pusat Notifikasi
                    </h1>
                </div>
                <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">Aktivitas dan pemberitahuan terbaru terkait akunmu</p>
            </div>

            <form action="{{ route('notifications.markRead') }}" method="POST">
                @csrf
                <button type="submit" class="sk-btn-ghost text-xs px-3 py-1.5 font-bold flex items-center gap-1.5 hover:text-[#588157]">
                    <svg class="w-3.5 h-3.5 text-[#588157]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="space-y-2.5">
            @forelse($notifications as $notif)
                <a href="{{ $notif->link_url ?? '#' }}" class="block p-3.5 sm:p-4 rounded-xl transition-all border {{ $notif->is_read ? 'bg-white dark:bg-zinc-900 border-slate-200 dark:border-zinc-800' : 'bg-emerald-50/50 dark:bg-emerald-950/20 border-emerald-300 dark:border-emerald-800 shadow-xs' }} hover:border-[#588157] hover:shadow-sm group">
                    <div class="flex items-start gap-3">
                        @if($notif->actor)
                            <div class="relative shrink-0">
                                <img src="{{ $notif->actor->avatar_url }}" alt="{{ $notif->actor->name }}" class="w-10 h-10 sm:w-11 sm:h-11 rounded-full object-cover ring-2 ring-emerald-200 group-hover:ring-[#588157] transition-all">
                                @if($notif->type === 'like')
                                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center ring-2 ring-white text-[10px]">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    </span>
                                @elseif($notif->type === 'comment')
                                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-blue-500 text-white flex items-center justify-center ring-2 ring-white text-[10px]">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    </span>
                                @elseif($notif->type === 'friend_request' || $notif->type === 'friend_accepted')
                                    <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-[#FF4500] text-white flex items-center justify-center ring-2 ring-white text-[10px]">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                    </span>
                                @endif
                            </div>
                        @elseif($notif->type === 'censored')
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 border border-amber-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        @elseif($notif->type === 'flagged')
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 border border-rose-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                        @else
                            <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-emerald-100 text-[#588157] flex items-center justify-center shrink-0 border border-emerald-300">
                                <svg class="w-5 h-5 text-[#588157]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-zinc-100">
                                        {{ $notif->title }}
                                    </h4>

                                    <!-- Badges -->
                                    @if($notif->type === 'like')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Suka
                                        </span>
                                    @elseif($notif->type === 'comment')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            Komentar
                                        </span>
                                    @elseif($notif->type === 'censored')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                            Sensor Otomatis
                                        </span>
                                    @elseif($notif->type === 'flagged')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            Peringatan Konten
                                        </span>
                                    @elseif($notif->type === 'thread_reply')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            Tongkrongan
                                        </span>
                                    @elseif($notif->type === 'friend_request' || $notif->type === 'friend_accepted')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-50 text-[#FF4500] border border-orange-200">
                                            Pertemanan
                                        </span>
                                    @elseif($notif->type === 'match_request' || $notif->type === 'match_accepted')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Teman Main
                                        </span>
                                    @endif
                                </div>
                                <span class="text-[10px] text-slate-500 dark:text-zinc-400 font-medium shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Clear Actor Identity & Info -->
                            @if($notif->actor)
                                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500 dark:text-zinc-400 flex-wrap">
                                    <span class="font-semibold text-slate-700 dark:text-zinc-300">{{ $notif->actor->name }}</span>
                                    <span>&bull;</span>
                                    <span class="text-slate-500 dark:text-zinc-400 font-medium">&#64;{{ $notif->actor->username }}</span>
                                    @if($notif->actor->school)
                                        <span>&bull;</span>
                                        <span class="text-slate-500 dark:text-zinc-400 truncate max-w-[200px]">{{ $notif->actor->school->school_name }}</span>
                                    @endif
                                </div>
                            @endif

                            <p class="text-xs text-slate-600 dark:text-zinc-300 mt-1 leading-relaxed font-normal">
                                {{ $notif->message }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center space-y-2">
                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-zinc-800 flex items-center justify-center mx-auto text-slate-400 dark:text-zinc-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">Belum ada notifikasi baru.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $notifications->links() }}
        </div>
    </div>

</div>
@endsection
