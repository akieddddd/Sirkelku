@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">

    <div class="sk-card space-y-4">
        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg sm:text-xl font-black text-slate-900">
                        Pusat Notifikasi
                    </h1>
                </div>
                <p class="text-xs text-slate-500 font-medium">Aktivitas dan pemberitahuan terbaru terkait akunmu</p>
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
        <div class="space-y-2">
            @forelse($notifications as $notif)
                <a href="{{ $notif->link_url ?? '#' }}" class="block p-3.5 rounded-xl transition-all border {{ $notif->is_read ? 'bg-white border-slate-200' : 'bg-emerald-50/60 border-emerald-200 shadow-xs' }} hover:border-emerald-300 hover:shadow-sm">
                    <div class="flex items-start gap-3">
                        @if($notif->actor)
                            <img src="{{ $notif->actor->avatar_url }}" alt="{{ $notif->actor->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 mt-0.5 ring-2 ring-emerald-200">
                        @else
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-[#588157] flex items-center justify-center text-xs font-bold shrink-0 border border-emerald-200">
                                <svg class="w-5 h-5 text-[#588157]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                    {{ $notif->title }}
                                </h4>
                                <span class="text-[10px] text-slate-400 font-medium shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-0.5 leading-relaxed font-medium">
                                {{ $notif->message }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center text-slate-400 text-xs font-medium">
                    Belum ada notifikasi baru untukmu.
                </div>
            @endforelse
        </div>

        <div class="pt-2">
            {{ $notifications->links() }}
        </div>
    </div>

</div>
@endsection

