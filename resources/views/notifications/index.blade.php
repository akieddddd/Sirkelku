@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <span class="px-3 py-1 rounded-full bg-pink-50 text-pink-600 text-[11px] font-extrabold uppercase tracking-wider">
                    Aktivitas Akun
                </span>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2">
                    Pusat Notifikasi
                </h1>
            </div>

            <form action="{{ route('notifications.markRead') }}" method="POST">
                @csrf
                <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>

        <!-- Notifications List -->
        <div class="space-y-2 pt-2">
            @forelse($notifications as $notif)
                <a href="{{ $notif->link_url ?? '#' }}" class="block p-4 rounded-2xl transition-all border {{ $notif->is_read ? 'bg-slate-50 border-slate-100' : 'bg-indigo-50/50 border-indigo-100 shadow-sm' }} hover:border-slate-300">
                    <div class="flex items-start gap-3">
                        @if($notif->actor)
                            <img src="{{ $notif->actor->avatar_url }}" alt="{{ $notif->actor->name }}" class="w-10 h-10 rounded-xl object-cover shrink-0 mt-0.5 ring-1 ring-slate-200">
                        @else
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-black shrink-0">
                                🔔
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-xs sm:text-sm font-bold text-slate-900 truncate">
                                    {{ $notif->title }}
                                </h4>
                                <span class="text-[10px] text-slate-400 shrink-0">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-600 mt-0.5 leading-relaxed">
                                {{ $notif->message }}
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center text-slate-400 text-xs">
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
