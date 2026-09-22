@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">

    <div class="sk-card space-y-4">
        <div class="flex items-center justify-between border-b border-amber-100 pb-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="sk-badge-orange text-[10px]">🔔 Realtime</span>
                    <h1 class="text-lg sm:text-xl font-black text-slate-900">
                        Pusat Notifikasi
                    </h1>
                </div>
                <p class="text-xs text-slate-500 font-medium">Aktivitas dan pemberitahuan terbaru terkait akunmu</p>
            </div>

            <form action="{{ route('notifications.markRead') }}" method="POST">
                @csrf
                <button type="submit" class="sk-btn-ghost text-xs px-3 py-1.5 font-bold">
                    ✓ Tandai Semua Dibaca
                </button>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="space-y-2">
            @forelse($notifications as $notif)
                <a href="{{ $notif->link_url ?? '#' }}" class="block p-3.5 rounded-xl transition-all border {{ $notif->is_read ? 'bg-white border-amber-100' : 'bg-amber-50/70 border-orange-200 shadow-xs' }} hover:border-orange-300 hover:shadow-sm">
                    <div class="flex items-start gap-3">
                        @if($notif->actor)
                            <img src="{{ $notif->actor->avatar_url }}" alt="{{ $notif->actor->name }}" class="w-10 h-10 rounded-full object-cover shrink-0 mt-0.5 ring-2 ring-orange-200">
                        @else
                            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs font-bold shrink-0 border border-orange-200">
                                🔔
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                    {{ $notif->title }}
                                </h4>
                                <span class="text-[10px] text-slate-400 font-medium shrink-0">🕒 {{ $notif->created_at->diffForHumans() }}</span>
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

