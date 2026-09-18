@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Community Profile Header Card -->
    <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm">
        <!-- Banner -->
        <div class="h-44 sm:h-52 w-full bg-slate-800 relative">
            <img src="{{ $community->banner_url }}" alt="{{ $community->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
        </div>

        <!-- Info Header -->
        <div class="p-6 sm:p-8 relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 -mt-16 sm:-mt-20 mb-4">
                <!-- Avatar & Title -->
                <div class="flex items-end gap-4">
                    <img src="{{ $community->avatar_url }}" alt="{{ $community->name }}" 
                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl object-cover ring-4 ring-white shadow-xl bg-white shrink-0">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $community->name }}</h1>
                            @if($community->hobby)
                                <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                    #{{ $community->hobby->name }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 flex items-center gap-2">
                            <span>👥 {{ $community->members_count }} Anggota</span>
                            <span>•</span>
                            <span>Dibuat oleh <strong class="text-slate-700">{{ $community->creator->name }}</strong></span>
                        </p>
                    </div>
                </div>

                <!-- Membership Join/Leave CTA -->
                <div>
                    @if($isMember)
                        <div class="flex items-center gap-2">
                            <span class="px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-extrabold flex items-center gap-1.5 border border-emerald-200">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                {{ $isAdmin ? 'Ketua Sirkel' : 'Sudah Bergabung' }}
                            </span>
                            @if(!$isAdmin)
                                <form action="{{ route('communities.leave', $community->id) }}" method="POST" onsubmit="return confirm('Keluar dari sirkel ini?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                        Keluar
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('communities.join', $community->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-extrabold shadow-md shadow-indigo-600/30 hover:scale-105 active:scale-95 transition-all flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Gabung Sirkel
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Description & School badge -->
            <div class="space-y-3 pt-3 border-t border-slate-100">
                <p class="text-sm text-slate-700 leading-relaxed font-normal">
                    {{ $community->description }}
                </p>
                <div class="flex items-center gap-3 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-100 text-slate-700 font-semibold">
                        🏫 Basis: {{ $community->school ? $community->school->school_name : 'Semua Pelajar Lintas Sekolah' }}
                    </span>
                    @if($community->school)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-slate-100 text-slate-600 font-semibold">
                            📍 {{ $community->school->city }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex items-center gap-2 pt-6 mt-4 border-t border-slate-100">
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'feed']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ $activeTab === 'feed' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    📸 Linimasa Sirkel
                </a>
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'forum']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ $activeTab === 'forum' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    💬 Forum Diskusi
                </a>
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'members']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-extrabold transition-all {{ $activeTab === 'members' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    👥 Anggota ({{ $community->members_count }})
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Content 1: Linimasa Internal Sirkel -->
    @if($activeTab === 'feed')
        <div class="space-y-4">
            <!-- Create Post Card specifically for this circle -->
            @if($isMember)
                <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200/80">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="community_id" value="{{ $community->id }}">
                        @if($community->hobby_id)
                            <input type="hidden" name="hobby_id" value="{{ $community->hobby_id }}">
                        @endif
                        <textarea name="content" rows="2" required placeholder="Bagikan cerita atau info kegiatan khusus untuk anggota {{ $community->name }}..."
                            class="w-full bg-slate-50 focus:bg-white text-sm p-3 rounded-2xl border border-slate-200 focus:border-indigo-500 focus:outline-none"></textarea>
                        <div class="flex items-center justify-between">
                            <label class="cursor-pointer text-xs font-bold text-slate-500 hover:text-indigo-600 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Lampirkan Foto</span>
                                <input type="file" name="image" accept="image/*" class="sr-only">
                            </label>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow">
                                Kirim ke Sirkel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Circle Posts -->
            <div class="space-y-4">
                @forelse($posts as $post)
                    <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-slate-200/80 space-y-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('profile.show', $post->user->username) }}">
                                <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-10 h-10 rounded-2xl object-cover ring-1 ring-slate-200">
                            </a>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">{{ $post->user->name }}</h4>
                                <p class="text-[11px] text-slate-400">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <p class="text-sm text-slate-800 leading-relaxed">{{ $post->content }}</p>

                        @if($post->image_url)
                            <div class="rounded-2xl overflow-hidden max-h-96">
                                <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                            </div>
                        @endif

                        <div class="flex items-center gap-4 pt-2 border-t border-slate-100 text-xs font-bold text-slate-500">
                            <span>❤️ {{ $post->likes_count }} Suka</span>
                            <span>💬 {{ $post->comments_count }} Komentar</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-3xl p-8 text-center text-slate-400 text-xs border border-slate-200/80">
                        Belum ada kiriman khusus di linimasa sirkel ini.
                    </div>
                @endforelse
            </div>
            <div>{{ $posts->links() }}</div>
        </div>

    <!-- Tab Content 2: Forum Diskusi Terkait -->
    @elseif($activeTab === 'forum')
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-extrabold text-sm text-slate-800">Utas Diskusi Sirkel</h3>
                <a href="{{ route('threads.create', ['community_id' => $community->id, 'hobby_id' => $community->hobby_id]) }}" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow">
                    + Buat Utas Sirkel
                </a>
            </div>

            <div class="space-y-3">
                @forelse($threads as $thread)
                    <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 rounded-3xl bg-white border border-slate-200/80 hover:border-slate-300 shadow-sm transition-all group">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="text-xs font-bold text-indigo-600">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                            <span class="text-[11px] text-slate-400">{{ $thread->comments_count }} tanggapan</span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $thread->title }}</h4>
                        <p class="text-xs text-slate-500 line-clamp-2 mt-1">{{ Str::limit($thread->body, 120) }}</p>
                    </a>
                @empty
                    <div class="bg-white rounded-3xl p-8 text-center text-slate-400 text-xs border border-slate-200/80">
                        Belum ada utas forum di sirkel ini. Mulai obrolan pertama!
                    </div>
                @endforelse
            </div>
            <div>{{ $threads->links() }}</div>
        </div>

    <!-- Tab Content 3: Daftar Anggota -->
    @elseif($activeTab === 'members')
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-200/80 space-y-4">
            <h3 class="font-extrabold text-sm text-slate-900">Daftar Anggota Sirkel</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($members as $member)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
                        <a href="{{ route('profile.show', $member->username) }}" class="flex items-center gap-3 min-w-0">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-10 h-10 rounded-xl object-cover ring-1 ring-slate-200">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $member->name }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ $member->school ? $member->school->school_name : 'Pelajar' }}</p>
                            </div>
                        </a>
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold {{ $member->pivot->role === 'admin' ? 'bg-amber-100 text-amber-800' : 'bg-slate-200 text-slate-700' }}">
                            {{ $member->pivot->role === 'admin' ? '👑 Ketua Sirkel' : 'Anggota' }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div>{{ $members->links() }}</div>
        </div>
    @endif

</div>
@endsection
