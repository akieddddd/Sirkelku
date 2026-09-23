@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-3xl shadow-sm border border-orange-100 overflow-hidden">
        <div class="p-6 border-b border-orange-100 bg-orange-50/30 flex justify-between items-center">
            <h1 class="text-xl font-black text-slate-900">Pesan Pribadi</h1>
        </div>
        
        <div class="divide-y divide-orange-50">
            @forelse($users as $user)
                <a href="{{ route('messages.show', $user->username) }}" class="flex items-center gap-4 p-4 hover:bg-orange-50/50 transition-colors">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full ring-2 ring-orange-100 object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="text-sm font-bold text-slate-900 truncate">{{ $user->name }}</h3>
                        </div>
                        <p class="text-xs text-slate-500 truncate">@<span>{{ $user->username }}</span></p>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 mb-1">Belum ada obrolan</h3>
                    <p class="text-sm text-slate-500">Mulai sapa teman baru melalui halaman profil mereka!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
