@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Clean Minimalist Header (Flat Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-200 pb-4">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-950 tracking-tight">
                Direktori Satu Sirkel
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500">
                Temukan sirkel hobi, komunitas sekolah, dan tongkrongan pelajar lintas daerah.
            </p>
        </div>
        <a href="{{ route('communities.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shrink-0 shadow-sm">
            <span>+</span> Bikin Sirkel Baru
        </a>
    </div>

    <!-- Flexible Search & Filter Bar -->
    <div class="bg-white rounded-xl p-3.5 shadow-sm border border-zinc-200">
        <form action="{{ route('communities.index') }}" method="GET" class="space-y-2.5">
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-2">
                <!-- Keyword Search -->
                <div class="relative flex-1">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama sirkel atau deskripsi..."
                        class="w-full pl-9 pr-3 py-2 bg-zinc-50 text-xs rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400">
                    <svg class="w-4 h-4 text-zinc-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>

                <!-- Hobby Filter -->
                <select name="hobby" class="px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
                    <option value="">Semua Hobi</option>
                    @foreach($hobbies as $hobby)
                        <option value="{{ $hobby->id }}" {{ request('hobby') == $hobby->id ? 'selected' : '' }}>
                            #{{ $hobby->name }}
                        </option>
                    @endforeach
                </select>

                <!-- School Filter -->
                <select name="school" class="px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
                    <option value="">Semua Sekolah</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>

                <!-- City Filter -->
                <select name="city" class="px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            {{ $city }}
                        </option>
                    @endforeach
                </select>

                <!-- Actions -->
                <div class="flex items-center gap-2 shrink-0">
                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs rounded-lg transition-colors">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'hobby', 'school', 'city']))
                        <a href="{{ route('communities.index') }}" class="px-3 py-2 text-xs text-zinc-500 hover:text-zinc-900 border border-zinc-200 rounded-lg transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
            
            <div class="text-[11px] text-zinc-400 px-0.5">
                Ditemukan <strong>{{ $communities->total() }}</strong> komunitas
            </div>
        </form>
    </div>

    <!-- Community Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @forelse($communities as $comm)
            <div class="bg-white rounded-xl overflow-hidden border border-zinc-200 shadow-sm hover:border-zinc-300 transition-all flex flex-col group">
                <!-- Banner Image -->
                <div class="h-28 w-full bg-zinc-100 relative overflow-hidden">
                    <img src="{{ $comm->banner_url }}" alt="{{ $comm->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    
                    @if($comm->hobby)
                        <span class="absolute top-2.5 right-2.5 px-2.5 py-0.5 rounded-full bg-zinc-900/80 backdrop-blur-xs text-[10px] font-medium text-white">
                            #{{ $comm->hobby->name }}
                        </span>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-4 flex-1 flex flex-col justify-between space-y-3 relative">
                    <!-- Avatar overlapping -->
                    <div class="-mt-9 flex items-end justify-between">
                        <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-12 h-12 rounded-lg object-cover ring-2 ring-white shadow-xs bg-white">
                        <span class="text-xs font-medium text-zinc-500 bg-zinc-50 border border-zinc-200 px-2 py-0.5 rounded-full">
                            {{ $comm->members_count }} Anggota
                        </span>
                    </div>

                    <div class="space-y-1">
                        <a href="{{ route('communities.show', $comm->slug) }}" class="font-semibold text-sm text-zinc-950 group-hover:text-blue-600 transition-colors line-clamp-1">
                            {{ $comm->name }}
                        </a>
                        <p class="text-xs text-zinc-600 line-clamp-2 leading-relaxed">
                            {{ $comm->description }}
                        </p>
                    </div>

                    <!-- Metadata -->
                    <div class="pt-2 border-t border-zinc-100 flex items-center justify-between text-xs text-zinc-500">
                        <span class="truncate">
                            {{ $comm->school ? $comm->school->school_name : 'Komunitas Terbuka' }}
                        </span>
                        <a href="{{ route('communities.show', $comm->slug) }}" class="font-semibold text-zinc-900 hover:text-blue-600 shrink-0">
                            Lihat &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl p-10 text-center border border-zinc-200 shadow-sm space-y-2">
                <h3 class="font-bold text-sm text-zinc-800">Tidak Ada Komunitas Ditemukan</h3>
                <p class="text-xs text-zinc-500 max-w-xs mx-auto">Coba ubah kata kunci atau buat sirkel baru.</p>
                <div class="pt-1">
                    <a href="{{ route('communities.create') }}" class="inline-block px-3.5 py-1.5 rounded-lg bg-zinc-900 text-white font-semibold text-xs">
                        + Bikin Sirkel Baru
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
