@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">

    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm border border-zinc-200 space-y-4">
        <div class="flex items-center justify-between border-b border-zinc-100 pb-3">
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-zinc-950">
                    Pusat Notifikasi
                </h1>
                <p class="text-xs text-zinc-500">Aktivitas dan pemberitahuan terkait akunmu</p>
            </div>

            <form action="{{ route('notifications.markRead') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-semibold text-zinc-600 hover:text-zinc-950 transition-colors">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="space-y-2">
            @forelse($notifications as $notif)
                <a href="{{ $notif->link_url ?? '#' }}" class="block p-3.5 rounded-lg transition-colors border {{ $notif->is_read ? 'bg-white border-zinc-200' : 'bg-zinc-50 border-zinc-300 shadow-2xs' }} hover:bg-zinc-50">
                    <div class="flex items-start gap-3">
                        @if($notif->actor)
                            <img src="{{ $notif->actor->avatar_url }}" alt="{{ $notif->actor->name }}" class="w-9 h-9 rounded-full object-cover shrink-0 mt-0.5 ring-1 ring-zinc-200">
                        @else
                            <div class="w-9 h-9 rounded-full bg-zinc-100 text-zinc-600 flex items-center justify-center text-xs font-bold shrink-0 border border-zinc-200">
                                Info
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-xs sm:text-sm font-semibold text-zinc-950 truncate">
                                    {{ $notif->title }}
                                </h4>
                                <span class="text-[10px] text-zinc-400 shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-zinc-600 mt-0.5 leading-relaxed">
                                {{ $notif->message }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-10 text-center text-zinc-400 text-xs">
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
