@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Community Profile Header Card -->
    <div class="bg-white rounded-xl overflow-hidden border border-zinc-200 shadow-sm">
        <!-- Banner -->
        <div class="h-40 sm:h-48 w-full bg-zinc-800 relative">
            <img src="{{ $community->banner_url }}" alt="{{ $community->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-zinc-950/60 via-transparent to-transparent"></div>
        </div>

        <!-- Info Header -->
        <div class="p-5 sm:p-6 relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 -mt-14 sm:-mt-16 mb-4">
                <!-- Avatar & Title -->
                <div class="flex items-end gap-3.5">
                    <img src="{{ $community->avatar_url }}" alt="{{ $community->name }}" 
                        class="w-18 h-18 sm:w-20 sm:h-20 rounded-xl object-cover ring-2 ring-white shadow-xs bg-white shrink-0">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-lg sm:text-xl font-bold text-zinc-950">{{ $community->name }}</h1>
                            @if($community->hobby)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-zinc-100 text-zinc-700 border border-zinc-200">
                                    #{{ $community->hobby->name }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-zinc-500 flex items-center gap-2">
                            <span>{{ $community->members_count }} Anggota</span>
                            <span>•</span>
                            <span>Oleh <strong class="text-zinc-700">{{ $community->creator->name }}</strong></span>
                        </p>
                    </div>
                </div>

                <!-- Membership Join/Leave CTA -->
                <div>
                    @if($isMember)
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 rounded-lg bg-zinc-100 text-zinc-800 text-xs font-semibold border border-zinc-200">
                                {{ $isAdmin ? 'Ketua Sirkel' : 'Sudah Bergabung' }}
                            </span>
                            @if(!$isAdmin)
                                <form action="{{ route('communities.leave', $community->id) }}" method="POST" onsubmit="return confirm('Keluar dari sirkel ini?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-zinc-400 hover:text-rose-600 hover:bg-rose-50 border border-zinc-200 transition-colors">
                                        Keluar
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('communities.join', $community->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white text-xs font-semibold transition-colors shadow-xs flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Gabung Sirkel
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Description & School badge -->
            <div class="space-y-2.5 pt-3 border-t border-zinc-100">
                <p class="text-xs sm:text-sm text-zinc-700 leading-relaxed font-normal">
                    {{ $community->description }}
                </p>
                <div class="flex items-center gap-2 text-xs text-zinc-500 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-zinc-100 text-zinc-700 font-medium border border-zinc-200">
                        Basis: {{ $community->school ? $community->school->school_name : 'Semua Pelajar' }}
                    </span>
                    @if($community->school)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-zinc-100 text-zinc-600 font-medium border border-zinc-200">
                            {{ $community->school->city }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex items-center gap-1.5 pt-4 mt-4 border-t border-zinc-100 overflow-x-auto">
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'feed']) }}"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $activeTab === 'feed' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                    Linimasa Sirkel
                </a>
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'forum']) }}"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $activeTab === 'forum' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                    Forum Diskusi
                </a>
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'members']) }}"
                    class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors {{ $activeTab === 'members' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                    Anggota ({{ $community->members_count }})
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Content 1: Linimasa Internal Sirkel -->
    @if($activeTab === 'feed')
        <div class="space-y-4">
            <!-- Create Post Card specifically for this circle -->
            @if($isMember)
                <div class="bg-white rounded-xl p-4 shadow-sm border border-zinc-200">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="community_id" value="{{ $community->id }}">
                        @if($community->hobby_id)
                            <input type="hidden" name="hobby_id" value="{{ $community->hobby_id }}">
                        @endif
                        <textarea name="content" rows="2" required placeholder="Bagikan kabar atau info kegiatan khusus anggota {{ $community->name }}..."
                            class="w-full bg-zinc-50 focus:bg-white text-xs sm:text-sm p-3 rounded-lg border border-zinc-200 focus:border-zinc-400 focus:outline-none"></textarea>
                        <div class="flex items-center justify-between">
                            <label class="cursor-pointer text-xs font-medium text-zinc-500 hover:text-zinc-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Lampirkan Foto</span>
                                <input type="file" name="image" accept="image/*" class="sr-only">
                            </label>
                            <button type="submit" class="px-3.5 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-semibold transition-colors">
                                Kirim ke Sirkel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Circle Posts -->
            <div class="space-y-3">
                @forelse($posts as $post)
                    <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-zinc-200 space-y-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('profile.show', $post->user->username) }}">
                                <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-9 h-9 rounded-full object-cover ring-1 ring-zinc-200">
                            </a>
                            <div>
                                <h4 class="text-xs sm:text-sm font-semibold text-zinc-950">{{ $post->user->name }}</h4>
                                <p class="text-[11px] text-zinc-400">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <p class="text-xs sm:text-sm text-zinc-800 leading-relaxed">{{ $post->content }}</p>

                        @if($post->image_url)
                            <div class="rounded-lg overflow-hidden max-h-96 border border-zinc-200 bg-zinc-900">
                                <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                            </div>
                        @endif

                        <div class="flex items-center gap-4 pt-2 border-t border-zinc-100 text-xs font-medium text-zinc-500">
                            <span>{{ $post->likes_count }} Suka</span>
                            <span>{{ $post->comments_count }} Komentar</span>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl p-8 text-center text-zinc-400 text-xs border border-zinc-200">
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
                <h3 class="font-bold text-sm text-zinc-950">Utas Diskusi Sirkel</h3>
                <a href="{{ route('threads.create', ['community_id' => $community->id, 'hobby_id' => $community->hobby_id]) }}" class="px-3.5 py-1.5 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors">
                    + Buat Utas Sirkel
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-zinc-200 divide-y divide-zinc-200 overflow-hidden">
                @forelse($threads as $thread)
                    <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 hover:bg-zinc-50 transition-colors group">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="text-xs font-medium text-zinc-600">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                            <span class="text-[11px] text-zinc-400">{{ $thread->comments_count }} tanggapan</span>
                        </div>
                        <h4 class="font-semibold text-sm text-zinc-950 group-hover:text-blue-600 transition-colors">{{ $thread->title }}</h4>
                        <p class="text-xs text-zinc-500 line-clamp-2 mt-1">{{ Str::limit($thread->body, 120) }}</p>
                    </a>
                @empty
                    <div class="p-8 text-center text-zinc-400 text-xs">
                        Belum ada utas forum di sirkel ini.
                    </div>
                @endforelse
            </div>
            <div>{{ $threads->links() }}</div>
        </div>

    <!-- Tab Content 3: Daftar Anggota -->
    @elseif($activeTab === 'members')
        <div class="bg-white rounded-xl p-5 shadow-sm border border-zinc-200 space-y-3">
            <h3 class="font-bold text-sm text-zinc-950">Daftar Anggota Sirkel</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                @foreach($members as $member)
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-zinc-50 border border-zinc-200">
                        <a href="{{ route('profile.show', $member->username) }}" class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-8 h-8 rounded-full object-cover ring-1 ring-zinc-200">
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-zinc-800 truncate">{{ $member->name }}</p>
                                <p class="text-[11px] text-zinc-400 truncate">{{ $member->school ? $member->school->school_name : 'Pelajar' }}</p>
                            </div>
                        </a>
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $member->pivot->role === 'admin' ? 'bg-zinc-900 text-white' : 'bg-zinc-200 text-zinc-700' }}">
                            {{ $member->pivot->role === 'admin' ? 'Ketua' : 'Anggota' }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div>{{ $members->links() }}</div>
        </div>
    @endif

</div>
@endsection
