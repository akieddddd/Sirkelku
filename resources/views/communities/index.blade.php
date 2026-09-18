@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Hero / Header Banner -->
    <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-pink-600 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-indigo-500/20 relative overflow-hidden">
        <div class="relative z-10 max-w-xl space-y-3">
            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[11px] font-extrabold uppercase tracking-wider text-white">
                Direktori Satu Sirkel
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight">
                Temukan Komunitas Hobi Lintas Sekolah
            </h1>
            <p class="text-xs sm:text-sm text-indigo-100 leading-relaxed">
                Gabung ke sirkel yang sesuai minatmu. Mabar game, jamming musik, kolaborasi seni, atau koding bareng teman-teman dari berbagai kota!
            </p>
            <div class="pt-2">
                <a href="{{ route('communities.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white text-indigo-600 font-extrabold text-xs shadow-md hover:bg-indigo-50 hover:scale-105 active:scale-95 transition-all">
                    <span>+</span> Bikin Sirkel Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Multifactor Search & Filter Bar -->
    <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200/80 space-y-4">
        <form action="{{ route('communities.index') }}" method="GET" class="space-y-3">
            <!-- Keyword Search -->
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama sirkel atau deskripsi..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 text-sm rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <!-- Filter Multifaktor (Hobi, Sekolah, Kota) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <!-- Hobby Filter -->
                <select name="hobby" class="w-full px-3 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Kategori Hobi</option>
                    @foreach($hobbies as $hobby)
                        <option value="{{ $hobby->id }}" {{ request('hobby') == $hobby->id ? 'selected' : '' }}>
                            #{{ $hobby->name }} ({{ $hobby->category }})
                        </option>
                    @endforeach
                </select>

                <!-- School Filter -->
                <select name="school" class="w-full px-3 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Asal Sekolah</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>

                <!-- City Filter -->
                <select name="city" class="w-full px-3 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-indigo-500">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                            📍 {{ $city }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between pt-1">
                <span class="text-xs text-slate-400 font-medium">Ditemukan {{ $communities->total() }} komunitas</span>
                <div class="flex items-center gap-2">
                    @if(request()->anyFilled(['q', 'hobby', 'school', 'city']))
                        <a href="{{ route('communities.index') }}" class="px-3 py-1.5 text-xs text-rose-500 hover:bg-rose-50 font-bold rounded-xl transition-colors">
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow transition-all">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Community Grid (12 per page) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @forelse($communities as $comm)
            <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col group">
                <!-- Banner Image -->
                <div class="h-28 w-full bg-slate-800 relative overflow-hidden">
                    <img src="{{ $comm->banner_url }}" alt="{{ $comm->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    
                    @if($comm->hobby)
                        <span class="absolute top-3 right-3 px-2.5 py-1 rounded-xl bg-slate-900/80 backdrop-blur-md text-[10px] font-extrabold text-white border border-white/20">
                            #{{ $comm->hobby->name }}
                        </span>
                    @endif
                </div>

                <!-- Card Body -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4 relative">
                    <!-- Avatar overlapping -->
                    <div class="-mt-11 flex items-end justify-between">
                        <img src="{{ $comm->avatar_url }}" alt="{{ $comm->name }}" class="w-14 h-14 rounded-2xl object-cover ring-4 ring-white shadow-md bg-white">
                        <span class="text-xs font-bold text-slate-500 flex items-center gap-1 bg-slate-100 px-2.5 py-1 rounded-xl">
                            👥 {{ $comm->members_count }} Anggota
                        </span>
                    </div>

                    <div class="space-y-1.5">
                        <a href="{{ route('communities.show', $comm->slug) }}" class="font-extrabold text-base text-slate-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                            {{ $comm->name }}
                        </a>
                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $comm->description }}
                        </p>
                    </div>

                    <!-- Metadata (School basis) -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                        <span class="truncate">
                            🏫 {{ $comm->school ? $comm->school->school_name : 'Komunitas Terbuka' }}
                        </span>
                        <a href="{{ route('communities.show', $comm->slug) }}" class="font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 shrink-0">
                            Lihat &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm space-y-3">
                <div class="w-16 h-16 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-3xl mx-auto">
                    🔍
                </div>
                <h3 class="font-extrabold text-base text-slate-800">Tidak Ada Komunitas Ditemukan</h3>
                <p class="text-xs text-slate-400 max-w-xs mx-auto">Coba ubah kata kunci atau buat sirkel pertamamu sekarang juga!</p>
                <a href="{{ route('communities.create') }}" class="inline-block mt-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-bold text-xs shadow">
                    + Bikin Sirkel Baru
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-2">
        {{ $communities->links() }}
    </div>

</div>
@endsection
