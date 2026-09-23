@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Community Profile Header Card -->
    <div class="sk-card overflow-hidden">
        <!-- Banner -->
        <div class="h-40 sm:h-48 w-full bg-slate-800 relative">
            <img src="{{ $community->banner_url }}" alt="{{ $community->name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent"></div>
        </div>

        <!-- Info Header -->
        <div class="p-5 sm:p-6 relative">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 -mt-14 sm:-mt-16 mb-4">
                <!-- Avatar & Title -->
                <div class="flex items-end gap-3.5">
                    <img src="{{ $community->avatar_url }}" alt="{{ $community->name }}" 
                        class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl object-cover ring-4 ring-white shadow-xs bg-white shrink-0">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800">{{ $community->name }}</h1>
                            @if($community->hobby)
                                <span class="sk-badge-sage">
                                    #{{ $community->hobby->name }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 font-medium flex items-center gap-2">
                            <span class="font-bold text-[#588157]">{{ $community->members_count }} Anggota</span>
                            <span>•</span>
                            <span>Oleh <strong class="text-slate-800 font-bold">{{ $community->creator->name }}</strong></span>
                        </p>
                    </div>
                </div>

                <!-- Membership Join/Leave CTA -->
                <div>
                    @if($isMember)
                        <div class="flex items-center gap-2">
                            <span class="sk-badge-sage text-xs py-1 px-3 flex items-center gap-1.5">
                                @if($isAdmin)
                                    <svg class="w-3.5 h-3.5 text-[#2D472C]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    <span>Ketua Sirkel</span>
                                @else
                                    <svg class="w-3.5 h-3.5 text-[#2D472C]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Sudah Bergabung</span>
                                @endif
                            </span>
                            @if(!$isAdmin)
                                <form action="{{ route('communities.leave', $community->id) }}" method="POST" onsubmit="return confirm('Keluar dari sirkel ini?')">
                                    @csrf
                                    <button type="submit" class="sk-btn-outline text-xs py-1.5 px-3 text-slate-500 hover:text-rose-600 hover:bg-rose-50">
                                        Keluar
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <form action="{{ route('communities.join', $community->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="sk-btn-primary text-xs py-2 px-4 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Gabung Sirkel
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Description & School badge -->
            <div class="space-y-2.5 pt-3.5 border-t border-slate-100">
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-medium">
                    {{ $community->description }}
                </p>
                <div class="flex items-center gap-2 text-xs text-slate-500 flex-wrap">
                    <span class="sk-badge-muted">
                        Basis: {{ $community->school ? $community->school->school_name : 'Semua Pelajar' }}
                    </span>
                    @if($community->school)
                        <span class="sk-badge-sage">
                            {{ $community->school->city }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="flex items-center gap-2 pt-4 mt-4 border-t border-slate-100 overflow-x-auto">
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'feed']) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'feed' ? 'bg-[#588157] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-[#588157]' }}">
                    Linimasa Sirkel
                </a>
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'forum']) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'forum' ? 'bg-[#588157] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-[#588157]' }}">
                    Forum Diskusi
                </a>
                <a href="{{ route('communities.show', ['slug' => $community->slug, 'tab' => 'members']) }}"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all {{ $activeTab === 'members' ? 'bg-[#588157] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-[#588157]' }}">
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
                <div class="sk-card p-4">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="community_id" value="{{ $community->id }}">
                        @if($community->hobby_id)
                            <input type="hidden" name="hobby_id" value="{{ $community->hobby_id }}">
                        @endif
                        <textarea name="content" rows="2.5" placeholder="Bagikan kabar atau info kegiatan khusus anggota {{ $community->name }}..."
                            class="sk-input text-xs sm:text-sm p-3"></textarea>
                        <div class="flex items-center justify-between">
                            <label class="cursor-pointer text-xs font-bold text-[#588157] hover:text-[#476A46] flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-[#588157]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Lampirkan Foto</span>
                                <input type="file" name="image" accept="image/*" class="sr-only">
                            </label>
                            <button type="submit" class="sk-btn-primary text-xs py-2 px-3.5">
                                Kirim ke Sirkel
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Circle Posts -->
            <div class="space-y-3.5">
                @forelse($posts as $post)
                    <div class="sk-card p-4 sm:p-5 space-y-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('profile.show', $post->user->username) }}">
                                <img src="{{ $post->user->avatar_url }}" alt="{{ $post->user->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-slate-200">
                            </a>
                            <div>
                                <h4 class="text-xs sm:text-sm font-bold text-slate-800">{{ $post->user->name }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">{{ $post->content }}</p>

                        @if($post->image_url)
                            <div class="rounded-xl overflow-hidden max-h-96 border border-slate-200 bg-slate-900">
                                <img src="{{ $post->image_url }}" alt="Post image" class="w-full h-auto object-cover">
                            </div>
                        @endif

                        <div class="flex items-center gap-4 pt-2.5 border-t border-slate-100 text-xs font-semibold text-slate-500">
                            <span>{{ $post->likes_count }} Suka</span>
                            <span>{{ $post->comments_count }} Komentar</span>
                        </div>
                    </div>
                @empty
                    <div class="sk-card p-8 text-center text-slate-400 text-xs font-medium">
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
                <a href="{{ route('threads.create', ['community_id' => $community->id, 'hobby_id' => $community->hobby_id]) }}" class="sk-btn-primary text-xs">
                    + Buat Utas Sirkel
                </a>
            </div>

            <div class="sk-card divide-y divide-slate-100 overflow-hidden">
                @forelse($threads as $thread)
                    <a href="{{ route('threads.show', $thread->id) }}" class="block p-4 hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="sk-badge-sage">#{{ $thread->hobby ? $thread->hobby->name : 'Diskusi' }}</span>
                            <span class="text-[11px] text-slate-400 font-semibold">{{ $thread->comments_count }} tanggapan</span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-800 group-hover:text-[#588157] transition-colors">{{ $thread->title }}</h4>
                        <p class="text-xs text-slate-500 line-clamp-2 mt-1 font-medium">{{ Str::limit($thread->body, 120) }}</p>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs font-medium">
                        Belum ada utas forum di sirkel ini.
                    </div>
                @endforelse
            </div>
            <div>{{ $threads->links() }}</div>
        </div>

    <!-- Tab Content 3: Daftar Anggota -->
    @elseif($activeTab === 'members')
        <div class="sk-card p-5 space-y-4">
            <h3 class="font-extrabold text-sm text-slate-800">Daftar Anggota Sirkel</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($members as $member)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-[#F8FAFC] border border-slate-200">
                        <a href="{{ route('profile.show', $member->username) }}" class="flex items-center gap-2.5 min-w-0">
                            <img src="{{ $member->avatar_url }}" alt="{{ $member->name }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-slate-200">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate hover:text-[#588157] transition-colors">{{ $member->name }}</p>
                                <p class="text-[11px] text-slate-400 font-medium truncate">{{ $member->school ? $member->school->school_name : 'Pelajar' }}</p>
                            </div>
                        </a>
                        <span class="sk-badge-muted text-[10px]">
                            {{ $member->pivot->role === 'admin' ? 'Ketua Sirkel' : 'Anggota' }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div>{{ $members->links() }}</div>
        </div>
    @endif

</div>
@endsection
