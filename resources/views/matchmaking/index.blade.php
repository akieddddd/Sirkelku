@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 rounded-3xl p-6 sm:p-8 text-white shadow-lg shadow-emerald-600/20">
        <div class="space-y-2 max-w-xl">
            <span class="px-3 py-1 rounded-full bg-white/20 backdrop-blur-md text-[11px] font-extrabold uppercase tracking-wider text-white">
                Quick Matchmaking
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight">Teman Main (Mabar & Kumpul)</h1>
            <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
                Cari partner mabar game, teman jamming akustik, rekan coding, atau lawan sparing futsal yang satu frekuensi dan satu kota.
            </p>
        </div>
    </div>

    <!-- Navigation Tabs (Jelajah Teman, Ajakan Masuk, Ajakan Terkirim, Teman Mabar) -->
    <div class="bg-white rounded-3xl p-2 sm:p-3 shadow-sm border border-slate-200/80 flex items-center justify-between gap-1 overflow-x-auto">
        <div class="flex items-center gap-1 sm:gap-2">
            <!-- Discover Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'discover']) }}"
                class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all shrink-0 {{ $tab === 'discover' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:bg-slate-100' }}">
                🔍 Jelajah Teman
            </a>

            <!-- Incoming Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'incoming']) }}"
                class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all shrink-0 flex items-center gap-1.5 {{ $tab === 'incoming' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:bg-slate-100' }}">
                <span>📥 Ajakan Masuk</span>
                @if($incomingRequests->isNotEmpty())
                    <span class="px-1.5 py-0.5 rounded-full text-[10px] font-black {{ $tab === 'incoming' ? 'bg-white text-emerald-700' : 'bg-pink-500 text-white' }}">
                        {{ $incomingRequests->count() }}
                    </span>
                @endif
            </a>

            <!-- Outgoing Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'outgoing']) }}"
                class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all shrink-0 {{ $tab === 'outgoing' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:bg-slate-100' }}">
                📤 Ajakan Terkirim
            </a>

            <!-- Friends Tab -->
            <a href="{{ route('matchmaking.index', ['tab' => 'friends']) }}"
                class="px-4 py-2.5 rounded-2xl text-xs font-extrabold transition-all shrink-0 {{ $tab === 'friends' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'text-slate-600 hover:bg-slate-100' }}">
                🤝 Teman Mabar ({{ $connectedFriends->count() }})
            </a>
        </div>
    </div>

    <!-- TAB 1: JELAJAH TEMAN (DISCOVER) -->
    @if($tab === 'discover')
        <!-- Filter Bar -->
        <div class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200/80">
            <form action="{{ route('matchmaking.index') }}" method="GET" class="space-y-3">
                <input type="hidden" name="tab" value="discover">
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <select name="hobby" class="w-full px-3 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Kesamaan Hobi</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->id }}" {{ request('hobby') == $hobby->id ? 'selected' : '' }}>
                                {{ $hobby->name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="school" class="w-full px-3 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Asal Sekolah</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="city" class="w-full px-3 py-2 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                📍 {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <span class="text-xs text-slate-400">Menampilkan pelajar sefrekuensi</span>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['hobby', 'school', 'city']))
                            <a href="{{ route('matchmaking.index', ['tab' => 'discover']) }}" class="text-xs text-rose-500 font-bold hover:underline">Reset</a>
                        @endif
                        <button type="submit" class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow">
                            Cari Partner
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Friends Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($users as $userItem)
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md hover:border-slate-300 transition-all flex flex-col justify-between space-y-4"
                    x-data="{ showMatchModal: false }">
                    
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <a href="{{ route('profile.show', $userItem->username) }}">
                                <img src="{{ $userItem->avatar_url }}" alt="{{ $userItem->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100">
                            </a>
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('profile.show', $userItem->username) }}" class="font-extrabold text-sm text-slate-900 hover:text-emerald-600 truncate block">
                                    {{ $userItem->name }}
                                </a>
                                <p class="text-[11px] text-slate-500 font-medium">@<span>{{ $userItem->username }}</span></p>
                                <p class="text-[11px] text-emerald-600 font-bold mt-0.5 truncate">
                                    🏫 {{ $userItem->school ? $userItem->school->school_name : 'Pelajar' }} ({{ $userItem->school ? $userItem->school->city : '-' }})
                                </p>
                            </div>
                        </div>

                        <!-- Bio -->
                        @if($userItem->bio)
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed bg-slate-50 p-2.5 rounded-xl">
                                "{{ $userItem->bio }}"
                            </p>
                        @endif

                        <!-- Hobbies list -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            @foreach($userItem->hobbies->take(3) as $hobby)
                                <span class="px-2 py-0.5 rounded-lg text-[10px] font-extrabold bg-slate-100 text-slate-700">
                                    #{{ $hobby->name }}
                                </span>
                            @endforeach
                            @if($userItem->hobbies->count() > 3)
                                <span class="text-[10px] text-slate-400 font-bold">+{{ $userItem->hobbies->count() - 3 }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('profile.show', $userItem->username) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800">
                            Profil Lengkap
                        </a>

                        <button type="button" @click="showMatchModal = true" 
                            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-sm hover:scale-105 transition-all flex items-center gap-1.5">
                            <span>🤝</span> Ajak Main
                        </button>
                    </div>

                    <!-- Modal Send Request -->
                    <div x-show="showMatchModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm">
                        <div @click.away="showMatchModal = false" class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-100">
                            <div class="flex items-center gap-3">
                                <img src="{{ $userItem->avatar_url }}" class="w-12 h-12 rounded-2xl object-cover">
                                <div>
                                    <h3 class="text-sm font-extrabold text-slate-900">Kirim Sinyal Ajak Main</h3>
                                    <p class="text-xs text-slate-500">Ke: <strong>{{ $userItem->name }}</strong></p>
                                </div>
                            </div>

                            <form action="{{ route('matchmaking.request') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $userItem->id }}">

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Hobi / Agenda:</label>
                                    <select name="hobby_id" class="w-full p-2.5 bg-slate-50 text-xs rounded-xl border border-slate-200">
                                        <option value="">-- Bebas / Nongkrong Santai --</option>
                                        @foreach($userItem->hobbies as $h)
                                            <option value="{{ $h->id }}">{{ $h->name }} ({{ $h->category }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Catatan / Ajakan (Opsional):</label>
                                    <textarea name="note" rows="3" placeholder="Yuk mabar akhir pekan ini atau sparing bareng..."
                                        class="w-full p-3 bg-slate-50 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"></textarea>
                                </div>

                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" @click="showMatchModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-100 rounded-xl">Batal</button>
                                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md">
                                        Kirim Sinyal Sekarang &rarr;
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-sm text-slate-400 text-xs">
                    Tidak ada teman ditemukan dengan filter saat ini.
                </div>
            @endforelse
        </div>
        <div>{{ $users->links() }}</div>

    <!-- TAB 2: AJAKAN MASUK (INCOMING) -->
    @elseif($tab === 'incoming')
        <div class="space-y-3">
            @forelse($incomingRequests as $req)
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $req->sender->avatar_url }}" alt="{{ $req->sender->name }}" class="w-12 h-12 rounded-2xl object-cover ring-1 ring-slate-200">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900">{{ $req->sender->name }}</h4>
                            <p class="text-xs text-slate-500">
                                {{ $req->sender->school ? $req->sender->school->school_name : 'Pelajar' }}
                                @if($req->hobby)
                                    • Mengajak untuk hobi <strong class="text-indigo-600">#{{ $req->hobby->name }}</strong>
                                @endif
                            </p>
                            @if($req->note)
                                <p class="text-xs text-slate-700 italic mt-1 bg-slate-50 px-2.5 py-1 rounded-lg">"{{ $req->note }}"</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-end sm:self-center">
                        <form action="{{ route('matchmaking.respond', $req->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="reject">
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                Tolak
                            </button>
                        </form>

                        <form action="{{ route('matchmaking.respond', $req->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-md transition-all">
                                Terima Ajakan 👍
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-slate-400 text-xs border border-slate-200/80">
                    Tidak ada ajakan main yang masuk saat ini.
                </div>
            @endforelse
        </div>

    <!-- TAB 3: AJAKAN TERKIRIM (OUTGOING) -->
    @elseif($tab === 'outgoing')
        <div class="space-y-3">
            @forelse($outgoingRequests as $req)
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $req->receiver->avatar_url }}" alt="{{ $req->receiver->name }}" class="w-11 h-11 rounded-2xl object-cover ring-1 ring-slate-200">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-900">{{ $req->receiver->name }}</h4>
                            <p class="text-xs text-slate-500">
                                {{ $req->receiver->school ? $req->receiver->school->school_name : 'Pelajar' }}
                                @if($req->hobby)
                                    • Target Hobi: #{{ $req->hobby->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        @if($req->status === 'accepted')
                            <span class="px-3 py-1 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">Diterima 🎉</span>
                        @elseif($req->status === 'rejected')
                            <span class="px-3 py-1 rounded-xl bg-rose-50 text-rose-700 text-xs font-bold border border-rose-200">Ditolak</span>
                        @else
                            <span class="px-3 py-1 rounded-xl bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">Menunggu Respons ⏳</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center text-slate-400 text-xs border border-slate-200/80">
                    Kamu belum mengirim ajakan main ke siapa pun. Cari partner di tab Jelajah Teman!
                </div>
            @endforelse
        </div>

    <!-- TAB 4: TEMAN MABAR (CONNECTED FRIENDS) -->
    @elseif($tab === 'friends')
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @forelse($connectedFriends as $friend)
                <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <a href="{{ route('profile.show', $friend->username) }}">
                            <img src="{{ $friend->avatar_url }}" alt="{{ $friend->name }}" class="w-12 h-12 rounded-2xl object-cover ring-1 ring-slate-200">
                        </a>
                        <div class="min-w-0">
                            <a href="{{ route('profile.show', $friend->username) }}" class="text-sm font-extrabold text-slate-900 hover:text-emerald-600 truncate block">
                                {{ $friend->name }}
                            </a>
                            <p class="text-[11px] text-slate-400 truncate">{{ $friend->school ? $friend->school->school_name : 'Pelajar' }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-black">
                                ✓ Teman Terhubung
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('profile.show', $friend->username) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors shrink-0">
                        Lihat Profil
                    </a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center text-slate-400 text-xs border border-slate-200/80">
                    Belum ada koneksi pertemanan yang terbentuk. Kirim sinyal Ajak Main ke teman-teman sefrekuensi!
                </div>
            @endforelse
        </div>
    @endif

</div>
@endsection
