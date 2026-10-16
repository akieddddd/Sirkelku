@extends('layouts.app')

@section('content')
<div class="space-y-5 p-4 sm:p-5">

    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-zinc-800 pb-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-zinc-100 tracking-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#FF4500] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Direktori Satu Sirkel
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 font-medium">
                Temukan sirkel hobi, komunitas sekolah, dan tongkrongan pelajar lintas daerah.
            </p>
        </div>
        <a href="{{ route('communities.create') }}" class="sk-btn-primary text-xs shrink-0">
            Buat Sirkel Baru
        </a>
    </div>

    <!-- Flexible Search & Filter Bar -->
    <div class="sk-card p-4">
        <form action="{{ route('communities.index') }}" method="GET" class="space-y-3">
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2.5">
                <!-- Keyword Search -->
                <div class="relative flex-1 flex items-center">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama sirkel atau deskripsi..."
                        class="sk-input pl-9 text-xs py-2 w-full">
                </div>

                <!-- Hobby Filter -->
                <select name="hobby" class="sk-input text-xs w-full md:w-auto font-semibold">
                    <option value="">Semua Hobi</option>
                    @foreach($hobbies as $hobby)
                        <option value="{{ $hobby->id }}" {{ request('hobby') == $hobby->id ? 'selected' : '' }}>
                            #{{ $hobby->name }}
                        </option>
                    @endforeach
                </select>

                <!-- School Filter -->
                <select name="school" class="sk-input text-xs w-full md:w-auto font-semibold">
                    <option value="">Semua Sekolah</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>

                <!-- City Filter -->
                <select name="city" class="sk-input text-xs w-full md:w-auto font-semibold">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>

                <!-- Actions -->
                <div class="flex items-center gap-2 shrink-0">
                    <button type="submit" class="sk-btn-primary text-xs py-2 px-4 w-full md:w-auto">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'hobby', 'school', 'city']))
                        <a href="{{ route('communities.index') }}" class="sk-btn-outline text-xs py-2 px-3">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="text-[11px] text-slate-400 font-medium px-0.5">
                Ditemukan <strong class="text-[#FF4500] font-bold">{{ $communities->total() }}</strong> komunitas
            </div>
        </form>
    </div>

    <!-- Community Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @forelse($communities as $comm)
            <div class="sk-card overflow-hidden flex flex-col justify-between group h-full">
                <!-- Banner Image -->
                <div class="h-28 w-full bg-slate-100 relative overflow-hidden">
                    <img src="{{ $comm->banner_url }}" alt="{{ $comm->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    @if($comm->hobby)
                        <span class="absolute top-2.5 right-2.5 sk-badge-sage backdrop-blur-xs">
                            #{{ $comm->hobby->name }}
                        </span>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-4 flex-1 flex flex-col justify-between space-y-3 relative">
                    <!-- Avatar overlapping -->
                    <div class="-mt-9 flex items-end justify-between">
                        <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-12 h-12 rounded-2xl object-cover ring-4 ring-white shadow-xs bg-white">
                        <span class="sk-badge-muted">
                            {{ $comm->members_count }} Anggota
                        </span>
                    </div>

                    <div class="space-y-1.5 flex-1">
                        <a href="{{ route('communities.show', $comm->slug) }}" class="font-bold text-sm text-slate-800 dark:text-zinc-100 group-hover:text-[#FF4500] dark:group-hover:text-[#FF4500] transition-colors line-clamp-1">
                            {{ $comm->name }}
                        </a>
                        <p class="text-xs text-slate-600 dark:text-zinc-300 font-medium line-clamp-2 leading-relaxed min-h-[2.5rem]">
                            {{ $comm->description }}
                        </p>
                    </div>

                    <!-- Metadata -->
                    <div class="pt-2.5 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between text-xs text-slate-500 dark:text-zinc-400 font-medium">
                        <span class="truncate text-[11px] text-slate-500 dark:text-zinc-400">
                            {{ $comm->school ? $comm->school->school_name : 'Komunitas Terbuka' }}
                        </span>
                        <a href="{{ route('communities.show', $comm->slug) }}" class="font-bold text-xs text-[#FF4500] hover:text-[#E03E00] shrink-0">
                            Lihat Sirkel &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full sk-card p-10 text-center space-y-3">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="font-bold text-sm text-slate-800 dark:text-zinc-100">Tidak ada komunitas ditemukan</h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 max-w-xs mx-auto font-medium">Ubah kata kunci pencarian atau buat sirkel baru.</p>
                <div class="pt-1">
                    <a href="{{ route('communities.create') }}" class="sk-btn-primary text-xs">
                        Buat Sirkel Baru
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $communities->links() }}
    </div>

</div>
@endsection
