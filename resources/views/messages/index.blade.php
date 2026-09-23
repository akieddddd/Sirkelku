@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="p-5 sm:p-6 border-b border-slate-200 bg-white flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#EAF0EA] flex items-center justify-center text-[#588157]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-black text-slate-800">Pesan Pribadi</h1>
                    <p class="text-xs text-slate-500">Obrolan langsung dan teman mabar sefrekuensi</p>
                </div>
            </div>
            <a href="{{ route('matchmaking.index') }}" class="text-xs font-bold text-[#2D472C] hover:text-[#1F361E] bg-[#EAF0EA] hover:bg-[#DFEADF] border border-[#CDE0CD] px-3 py-1.5 rounded-xl transition-all flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Cari Teman</span>
            </a>
        </div>
        
        <!-- Conversation List -->
        <div class="divide-y divide-slate-100">
            @forelse($users as $user)
                <a href="{{ route('messages.show', $user->username) }}" 
                   class="group flex items-center gap-4 p-4 sm:p-5 hover:bg-slate-50 transition-all duration-150 {{ ($user->unread_from_user ?? 0) > 0 ? 'bg-[#EAF0EA]/30' : '' }}">
                    <div class="relative shrink-0">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-13 h-13 sm:w-14 sm:h-14 rounded-full ring-2 {{ ($user->unread_from_user ?? 0) > 0 ? 'ring-[#588157] ring-offset-2' : 'ring-slate-200' }} object-cover transition-transform group-hover:scale-105 duration-200">
                        @if(($user->unread_from_user ?? 0) > 0)
                            <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1.5 bg-rose-500 text-white text-[11px] font-black rounded-full flex items-center justify-center ring-2 ring-white shadow-sm">
                                {{ $user->unread_from_user > 9 ? '9+' : $user->unread_from_user }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="text-sm font-bold text-slate-800 group-hover:text-[#588157] truncate transition-colors flex items-center gap-2">
                                <span>{{ $user->name }}</span>
                                @if(($user->unread_from_user ?? 0) > 0)
                                    <span class="inline-block w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                                @endif
                            </h3>
                            @if($user->latest_message)
                                <span class="device-time text-[11px] font-semibold {{ ($user->unread_from_user ?? 0) > 0 ? 'text-[#588157]' : 'text-slate-400' }}" 
                                      data-timestamp="{{ $user->latest_message->created_at->toIso8601String() }}">
                                    {{ $user->latest_message->created_at->format('H:i') }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-xs {{ ($user->unread_from_user ?? 0) > 0 ? 'font-bold text-slate-800' : 'text-slate-500' }} truncate">
                                @if($user->latest_message)
                                    @if($user->latest_message->sender_id === Auth::id())
                                        <span class="text-slate-400 font-normal">Anda: </span>
                                    @endif
                                    {{ $user->latest_message->content }}
                                @else
                                    <span class="text-slate-400 italic">Mulai percakapan baru</span>
                                @endif
                            </p>
                            <svg class="w-4 h-4 text-slate-300 group-hover:text-[#588157] group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-[#EAF0EA] rounded-2xl flex items-center justify-center mx-auto mb-4 border border-[#CDE0CD] text-[#588157]">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Belum ada obrolan</h3>
                    <p class="text-sm text-slate-500 max-w-sm mx-auto mb-5">Temukan teman satu sirkel atau teman main sehobi dan mulailah saling bertukar pesan!</p>
                    <a href="{{ route('matchmaking.index') }}" class="sk-btn-primary text-xs py-2 px-4 inline-flex items-center gap-2">
                        <span>Cari Teman Main</span>
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    // Format all timestamps according to user's device settings & locale
    document.addEventListener('DOMContentLoaded', () => {
        const timeFormatter = new Intl.DateTimeFormat(navigator.language || 'id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });

        document.querySelectorAll('.device-time').forEach(el => {
            const iso = el.getAttribute('data-timestamp');
            if (iso) {
                try {
                    const date = new Date(iso);
                    const now = new Date();
                    const isToday = date.toDateString() === now.toDateString();
                    
                    if (isToday) {
                        el.textContent = timeFormatter.format(date);
                    } else {
                        const dateFormatter = new Intl.DateTimeFormat(navigator.language || 'id-ID', {
                            day: 'numeric',
                            month: 'short'
                        });
                        el.textContent = dateFormatter.format(date);
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        });
    });
</script>
@endsection
