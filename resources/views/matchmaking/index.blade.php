@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- Clean Minimalist Header (Flat Style) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-200 pb-4">
        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-950 tracking-tight">
                Teman Main (Mabar & Sparing)
            </h1>
            <p class="text-xs sm:text-sm text-zinc-500">
                Cari partner mabar game, jamming musik, rekan belajar, atau sparing olahraga sefrekuensi.
            </p>
        </div>
    </div>

    <!-- Navigation Tabs (Jelajah Teman, Ajakan Masuk, Ajakan Terkirim, Teman Mabar) -->
    <div class="bg-white rounded-xl p-1.5 shadow-sm border border-zinc-200 flex items-center justify-between gap-1 overflow-x-auto">
        <div class="flex items-center gap-1">
            <!-- Discover Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'discover']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0 {{ $tab === 'discover' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Jelajah Teman
            </a>

            <!-- Incoming Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'incoming']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0 flex items-center gap-1.5 {{ $tab === 'incoming' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                <span>Ajakan Masuk</span>
                @if($incomingRequests->isNotEmpty())
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-bold {{ $tab === 'incoming' ? 'bg-white text-zinc-900' : 'bg-zinc-900 text-white' }}">
                        {{ $incomingRequests->count() }}
                    </span>
                @endif
            </a>

            <!-- Outgoing Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'outgoing']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0 {{ $tab === 'outgoing' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Ajakan Terkirim
            </a>

            <!-- Friends Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'friends']) }}"
                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-colors shrink-0 {{ $tab === 'friends' ? 'bg-zinc-900 text-white' : 'text-zinc-600 hover:bg-zinc-100' }}">
                Teman Terhubung ({{ $connectedFriends->count() }})
            </a>
        </div>
    </div>

    <!-- TAB 1: JELAJAH TEMAN (DISCOVER) -->
    @if($tab === 'discover')
        <!-- Filter Bar -->
        <div class="bg-white rounded-xl p-3.5 shadow-sm border border-zinc-200">
            <form action="{{ route('matchmaking.index') }}" method="GET" class="space-y-2.5">
                <input type="hidden" name="tab" value="discover">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    <select name="hobby" class="w-full px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
                        <option value="">Semua Kesamaan Hobi</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->id }}" {{ request('hobby') == $hobby->id ? 'selected' : '' }}>
                                {{ $hobby->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="school" class="w-full px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
                        <option value="">Semua Asal Sekolah</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="city" class="w-full px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <span class="text-xs text-zinc-400">Menampilkan pelajar sefrekuensi</span>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['hobby', 'school', 'city']))
                            <a href="{{ route('matchmaking.index', ['tab' => 'discover']) }}" class="text-xs text-zinc-500 hover:text-zinc-900 border border-zinc-200 px-3 py-1.5 rounded-lg transition-colors">Reset</a>
                        @endif
                        <button type="submit" class="px-4 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs rounded-lg transition-colors">
                            Cari Partner
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Friends Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($users as $userItem)
                <div class="bg-white rounded-xl p-4 border border-zinc-200 shadow-sm hover:border-zinc-300 transition-all flex flex-col justify-between space-y-3.5"
                    x-data="{ showMatchModal: false }">
                    
                    <div class="space-y-2.5">
                        <div class="flex items-start gap-3">
                            <a href="{{ route('profile.show', $userItem->username) }}">
                                <img src="{{ $userItem->avatar_url }}" alt="{{ $userItem->name }}" class="w-12 h-12 rounded-full object-cover ring-1 ring-zinc-200">
                            </a>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('profile.show', $userItem->username) }}" class="font-semibold text-sm text-zinc-950 hover:text-blue-600 truncate block">
                                    {{ $userItem->name }}
                                </a>
                                <p class="text-[11px] text-zinc-400">@<span>{{ $userItem->username }}</span></p>
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-zinc-100 text-zinc-600 text-[11px] font-medium border border-zinc-200 truncate max-w-full">
                                    {{ $userItem->school ? $userItem->school->school_name : 'Pelajar' }}
                                </span>
                            </div>
                        </div>

                        <!-- Bio (Uniform Height) -->
                        <div class="min-h-[2.75rem] flex flex-col justify-center">
                            @if($userItem->bio)
                                <p class="text-xs text-zinc-600 line-clamp-2 leading-relaxed bg-zinc-50 p-2 rounded-lg border border-zinc-100">
                                    "{{ $userItem->bio }}"
                                </p>
                            @else
                                <p class="text-xs text-zinc-400 italic leading-relaxed bg-zinc-50/60 p-2 rounded-lg border border-dashed border-zinc-200/70">
                                    Belum menambahkan bio perkenalan.
                                </p>
                            @endif
                        </div>

                        <!-- Hobbies list -->
                        <div class="flex flex-wrap gap-1.5 pt-0.5">
                            @foreach($userItem->hobbies->take(3) as $hobby)
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-zinc-100 text-zinc-700 border border-zinc-200">
                                    #{{ $hobby->name }}
                                </span>
                            @endforeach
                            @if($userItem->hobbies->count() > 3)
                                <span class="text-[11px] text-zinc-400 font-medium self-center">+{{ $userItem->hobbies->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2.5 border-t border-zinc-100 flex items-center justify-between">
                        <a href="{{ route('profile.show', $userItem->username) }}" class="text-xs font-medium text-zinc-500 hover:text-zinc-950">
                            Lihat Profil
                        </a>

                        <button type="button" @click="showMatchModal = true" 
                            class="px-3.5 py-1.5 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shadow-xs">
                            Ajak Main
                        </button>
                    </div>

                    <!-- Modal Send Request -->
                    <div x-show="showMatchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-zinc-950/50 backdrop-blur-xs">
                        <div @click.away="showMatchModal = false" class="bg-white rounded-xl p-5 max-w-md w-full shadow-lg space-y-4 border border-zinc-200">
                            <div class="flex items-center gap-3">
                                <img src="{{ $userItem->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <h3 class="text-sm font-bold text-zinc-950">Kirim Ajakan Main</h3>
                                    <p class="text-xs text-zinc-500">Kepada: <strong>{{ $userItem->name }}</strong></p>
                                </div>
                            </div>

                            <form action="{{ route('matchmaking.request') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $userItem->id }}">

                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Pilih Hobi / Agenda:</label>
                                    <select name="hobby_id" class="w-full p-2 bg-zinc-50 text-xs rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400">
                                        <option value="">-- Bebas / Nongkrong Santai --</option>
                                        @foreach($userItem->hobbies as $h)
                                            <option value="{{ $h->id }}">{{ $h->name }} ({{ $h->category }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-zinc-700 mb-1">Catatan / Pesan (Opsional):</label>
                                    <textarea name="note" rows="3" placeholder="Yuk mabar akhir pekan ini atau diskusi bareng..."
                                        class="w-full p-2.5 bg-zinc-50 text-xs rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400"></textarea>
                                </div>

                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" @click="showMatchModal = false" class="px-3 py-1.5 text-xs font-semibold text-zinc-500 hover:bg-zinc-100 rounded-lg">Batal</button>
                                    <button type="submit" class="px-4 py-1.5 bg-zinc-900 hover:bg-zinc-800 text-white rounded-lg text-xs font-semibold transition-colors">
                                        Kirim Ajakan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl p-10 text-center border border-zinc-200 shadow-sm text-zinc-400 text-xs">
                    Tidak ada teman ditemukan dengan filter saat ini.
                </div>
            @endforelse
        </div>
        <div>{{ $users->links() }}</div>

    <!-- TAB 2: AJAKAN MASUK (INCOMING) -->
    @elseif($tab === 'incoming')
        <div class="space-y-3">
            @forelse($incomingRequests as $req)
                <div class="bg-white rounded-xl p-4 border border-zinc-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $req->sender->avatar_url }}" alt="{{ $req->sender->name }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-zinc-200">
                        <div>
                            <h4 class="text-sm font-semibold text-zinc-950">{{ $req->sender->name }}</h4>
                            <p class="text-xs text-zinc-500">
                                {{ $req->sender->school ? $req->sender->school->school_name : 'Pelajar' }}
                                @if($req->hobby)
                                    • Hobi: <strong class="text-zinc-800">#{{ $req->hobby->name }}</strong>
                                @endif
                            </p>
                            @if($req->note)
                                <p class="text-xs text-zinc-700 italic mt-1 bg-zinc-50 px-2 py-1 rounded-md border border-zinc-100">"{{ $req->note }}"</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-center">
                        <form action="{{ route('matchmaking.respond', $req->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-zinc-500 hover:text-rose-600 hover:bg-rose-50 border border-zinc-200 transition-colors">
                                Tolak
                            </button>
                        </form>

                        <form action="{{ route('matchmaking.respond', $req->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="px-3.5 py-1.5 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors">
                                Terima Ajakan
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-10 text-center text-zinc-400 text-xs border border-zinc-200">
                    Tidak ada ajakan main yang masuk saat ini.
                </div>
            @endforelse
        </div>

    <!-- TAB 3: AJAKAN TERKIRIM (OUTGOING) -->
    @elseif($tab === 'outgoing')
        <div class="space-y-3">
            @forelse($outgoingRequests as $req)
                <div class="bg-white rounded-xl p-4 border border-zinc-200 shadow-sm flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $req->receiver->avatar_url }}" alt="{{ $req->receiver->name }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-zinc-200">
                        <div>
                            <h4 class="text-sm font-semibold text-zinc-950">{{ $req->receiver->name }}</h4>
                            <p class="text-xs text-zinc-500">
                                {{ $req->receiver->school ? $req->receiver->school->school_name : 'Pelajar' }}
                                @if($req->hobby)
                                    • Target: #{{ $req->hobby->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        @if($req->status === 'accepted')
                            <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">Diterima</span>
                        @elseif($req->status === 'rejected')
                            <span class="px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-semibold border border-rose-200">Ditolak</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full bg-zinc-100 text-zinc-700 text-xs font-semibold border border-zinc-200">Menunggu</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-10 text-center text-zinc-400 text-xs border border-zinc-200">
                    Kamu belum mengirim ajakan main ke siapa pun. Cari partner di tab Jelajah Teman!
                </div>
            @endforelse
        </div>

    <!-- TAB 4: TEMAN MABAR (CONNECTED FRIENDS) -->
    @elseif($tab === 'friends')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($connectedFriends as $friend)
                <div class="bg-white rounded-xl p-4 border border-zinc-200 shadow-sm flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <a href="{{ route('profile.show', $friend->username) }}">
                            <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-zinc-200">
                        </a>
                        <div class="min-w-0">
                            <a href="{{ route('profile.show', $friend->username) }}" class="text-sm font-semibold text-zinc-950 hover:text-blue-600 truncate block">
                                {{ $friend->name }}
                            </a>
                            <p class="text-[11px] text-zinc-400 truncate">{{ $friend->school ? $friend->school->school_name : 'Pelajar' }}</p>
                            <span class="inline-block mt-0.5 px-2 py-0.2 rounded-md bg-zinc-100 text-zinc-700 text-[10px] font-medium border border-zinc-200">
                                Teman Terhubung
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('profile.show', $friend->username) }}" class="px-3 py-1.5 rounded-lg border border-zinc-200 hover:bg-zinc-50 text-zinc-700 font-medium text-xs transition-colors shrink-0">
                        Profil
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl p-10 text-center text-zinc-400 text-xs border border-zinc-200">
                    Belum ada koneksi pertemanan yang terbentuk. Kirim ajakan main ke teman-teman sefrekuensi!
                </div>
            @endforelse
        </div>
    @endif

</div>
@endsection
