@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Page Header -->
    <div class="sk-card">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="sk-badge-sage">Sefrekuensi</span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">
                        Teman Main (Mabar & Sparing)
                    </h1>
                </div>
                <p class="text-xs sm:text-sm text-slate-600">
                    Cari partner mabar game, jamming musik, rekan belajar, atau sparing olahraga sefrekuensi dari berbagai sekolah.
                </p>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-1.5 pt-4 mt-4 border-t border-slate-100 overflow-x-auto">
            <!-- Discover Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'discover']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $tab === 'discover' ? 'bg-[#588157] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-[#588157]' }}">
                Jelajah Teman
            </a>

            <!-- Incoming Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'incoming']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 flex items-center gap-2 {{ $tab === 'incoming' ? 'bg-[#588157] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-[#588157]' }}">
                <span>Ajakan Masuk</span>
                @if($incomingRequests->isNotEmpty())
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $tab === 'incoming' ? 'bg-white text-[#2D472C]' : 'bg-[#588157] text-white' }}">
                        {{ $incomingRequests->count() }}
                    </span>
                @endif
            </a>

            <!-- Outgoing Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'outgoing']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $tab === 'outgoing' ? 'bg-[#588157] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-[#588157]' }}">
                Ajakan Terkirim
            </a>

            <!-- Friends Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'friends']) }}"
                class="px-4 py-2 rounded-xl text-xs font-bold transition-all shrink-0 {{ $tab === 'friends' ? 'bg-[#588157] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-[#588157]' }}">
                Teman Terhubung ({{ $connectedFriends->count() }})
            </a>
        </div>
    </div>

    <!-- TAB 1: JELAJAH TEMAN (DISCOVER) -->
    @if($tab === 'discover')
        <!-- Filter Bar -->
        <div class="sk-card-compact">
            <form action="{{ route('matchmaking.index') }}" method="GET" class="space-y-3">
                <input type="hidden" name="tab" value="discover">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <select name="hobby" class="sk-select text-xs">
                        <option value="">Semua Kesamaan Hobi</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->id }}" {{ request('hobby') == $hobby->id ? 'selected' : '' }}>
                                {{ $hobby->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="school" class="sk-select text-xs">
                        <option value="">Semua Asal Sekolah</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="city" class="sk-select text-xs">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <span class="text-xs text-slate-500 font-medium">Menampilkan pelajar sefrekuensi</span>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['hobby', 'school', 'city']))
                            <a href="{{ route('matchmaking.index', ['tab' => 'discover']) }}" class="sk-btn-outline text-xs px-3 py-1.5">Reset</a>
                        @endif
                        <button type="submit" class="sk-btn-primary text-xs px-4 py-1.5">
                            Cari Partner
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Friends Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($users as $userItem)
                <div class="sk-card flex flex-col justify-between space-y-3.5 hover:border-slate-300 transition-all"
                    x-data="{ showMatchModal: false }">
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <a href="{{ route('profile.show', $userItem->username) }}" class="relative group">
                                <img src="{{ $userItem->avatar_url }}" alt="{{ $userItem->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-slate-200 group-hover:ring-[#588157] transition-all">
                            </a>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('profile.show', $userItem->username) }}" class="font-bold text-sm text-slate-800 hover:text-[#588157] truncate block transition-colors">
                                    {{ $userItem->name }}
                                </a>
                                <p class="text-[11px] text-slate-400 font-medium">@<span>{{ $userItem->username }}</span></p>
                                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full bg-[#EAF0EA] text-[#2D472C] text-[11px] font-semibold border border-[#CDE0CD] truncate max-w-full">
                                    {{ $userItem->school ? $userItem->school->school_name : 'Pelajar' }}
                                </span>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="min-h-[2.75rem] flex flex-col justify-center">
                            @if($userItem->bio)
                                <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed bg-[#F8FAFC] p-2.5 rounded-xl border border-slate-200">
                                    "{{ $userItem->bio }}"
                                </p>
                            @else
                                <p class="text-xs text-slate-400 italic leading-relaxed bg-slate-50 p-2.5 rounded-xl border border-dashed border-slate-200">
                                    Belum menambahkan bio perkenalan.
                                </p>
                            @endif
                        </div>

                        <!-- Hobbies list -->
                        <div class="flex flex-wrap gap-1.5 pt-0.5">
                            @foreach($userItem->hobbies->take(3) as $hobby)
                                <span class="sk-pill text-[11px]">
                                    #{{ $hobby->name }}
                                </span>
                            @endforeach
                            @if($userItem->hobbies->count() > 3)
                                <span class="text-[11px] text-slate-400 font-bold self-center">+{{ $userItem->hobbies->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('profile.show', $userItem->username) }}" class="text-xs font-semibold text-slate-500 hover:text-[#588157] transition-colors">
                            Lihat Profil
                        </a>

                        <button type="button" @click="showMatchModal = true" 
                            class="sk-btn-primary text-xs px-3.5 py-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <span>Ajak Main</span>
                        </button>
                    </div>

                    <!-- Modal Send Request -->
                    <div x-show="showMatchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
                        <div @click.away="showMatchModal = false" class="sk-card max-w-md w-full shadow-2xl space-y-4 border border-slate-200">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                                <img src="{{ $userItem->avatar_url }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-slate-200">
                                <div>
                                    <h3 class="text-base font-bold text-slate-800">Kirim Ajakan Main</h3>
                                    <p class="text-xs text-slate-500">Kepada: <strong class="text-[#588157]">{{ $userItem->name }}</strong></p>
                                </div>
                            </div>

                            <form action="{{ route('matchmaking.request') }}" method="POST" class="space-y-3.5">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $userItem->id }}">

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Hobi / Agenda:</label>
                                    <select name="hobby_id" class="sk-select text-xs">
                                        <option value="">-- Bebas / Nongkrong Santai --</option>
                                        @foreach($userItem->hobbies as $h)
                                             <option value="{{ $h->id }}">{{ $h->name }} ({{ $h->category }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Pesan (Opsional):</label>
                                    <textarea name="note" rows="3" placeholder="Yuk mabar akhir pekan ini atau diskusi bareng..."
                                        class="sk-input text-xs"></textarea>
                                </div>

                                <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                                    <button type="button" @click="showMatchModal = false" class="sk-btn-ghost text-xs px-3 py-1.5">Batal</button>
                                    <button type="submit" class="sk-btn-primary text-xs px-4 py-1.5">
                                        Kirim Ajakan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full sk-card p-10 text-center text-slate-400 text-xs">
                    Tidak ada teman ditemukan dengan filter saat ini.
                </div>
            @endforelse
        </div>
        <div>{{ $users->links() }}</div>

    <!-- TAB 2: AJAKAN MASUK (INCOMING) -->
    @elseif($tab === 'incoming')
        <div class="space-y-3">
            @forelse($incomingRequests as $req)
                <div class="sk-card flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $req->sender->avatar_url }}" alt="{{ $req->sender->name }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-slate-200">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">{{ $req->sender->name }}</h4>
                            <p class="text-xs text-slate-500">
                                {{ $req->sender->school ? $req->sender->school->school_name : 'Pelajar' }}
                                @if($req->hobby)
                                    • Hobi: <strong class="text-[#588157]">#{{ $req->hobby->name }}</strong>
                                @endif
                            </p>
                            @if($req->note)
                                <p class="text-xs text-slate-700 italic mt-1.5 bg-[#F8FAFC] px-3 py-1.5 rounded-xl border border-slate-200">"{{ $req->note }}"</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-center">
                        <form action="{{ route('matchmaking.respond', $req->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 transition-all">
                                Tolak
                            </button>
                        </form>

                        <form action="{{ route('matchmaking.respond', $req->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="sk-btn-primary text-xs px-4 py-1.5">
                                Terima Ajakan
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="sk-card p-10 text-center text-slate-400 text-xs">
                    Tidak ada ajakan main yang masuk saat ini.
                </div>
            @endforelse
        </div>

    <!-- TAB 3: AJAKAN TERKIRIM (OUTGOING) -->
    @elseif($tab === 'outgoing')
        <div class="space-y-3">
            @forelse($outgoingRequests as $req)
                <div class="sk-card flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $req->receiver->avatar_url }}" alt="{{ $req->receiver->name }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-slate-200">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">{{ $req->receiver->name }}</h4>
                            <p class="text-xs text-slate-500">
                                {{ $req->receiver->school ? $req->receiver->school->school_name : 'Pelajar' }}
                                @if($req->hobby)
                                    • Target: #{{ $req->hobby->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        @if($req->status === 'accepted')
                            <span class="sk-badge-sage text-xs">Diterima</span>
                        @elseif($req->status === 'rejected')
                            <span class="px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">Ditolak</span>
                        @else
                            <span class="sk-badge-muted text-xs">Menunggu</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="sk-card p-10 text-center text-slate-400 text-xs">
                    Kamu belum mengirim ajakan main ke siapa pun. Cari partner di tab Jelajah Teman!
                </div>
            @endforelse
        </div>

    <!-- TAB 4: TEMAN MABAR (CONNECTED FRIENDS) -->
    @elseif($tab === 'friends')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($connectedFriends as $friend)
                <div class="sk-card flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <a href="{{ route('profile.show', $friend->username) }}">
                            <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}" class="w-11 h-11 rounded-full object-cover ring-2 ring-slate-200">
                        </a>
                        <div class="min-w-0">
                            <a href="{{ route('profile.show', $friend->username) }}" class="text-sm font-bold text-slate-800 hover:text-[#588157] truncate block transition-colors">
                                {{ $friend->name }}
                            </a>
                            <p class="text-[11px] text-slate-400 truncate font-medium">{{ $friend->school ? $friend->school->school_name : 'Pelajar' }}</p>
                            <span class="sk-badge-sage text-[10px] mt-1">
                                Teman Terhubung
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('profile.show', $friend->username) }}" class="sk-btn-outline text-xs px-3 py-1.5 shrink-0">
                        Profil
                    </a>
                </div>
            @empty
                <div class="col-span-full sk-card p-10 text-center text-slate-400 text-xs">
                    Belum ada koneksi pertemanan yang terbentuk. Kirim ajakan main ke teman-teman sefrekuensi!
                </div>
            @endforelse
        </div>
    @endif

</div>
@endsection
