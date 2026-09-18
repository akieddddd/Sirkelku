@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
        <a href="{{ route('threads.index') }}" class="hover:text-indigo-600 flex items-center gap-1">
            &larr; Kembali ke Forum Tongkrongan
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
        <div>
            <span class="px-3 py-1 rounded-full bg-violet-50 text-violet-600 text-[11px] font-extrabold uppercase tracking-wider">
                Mulai Diskusi Baru
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2">
                Bikin Utas di Tongkrongan.id
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Tulis pertanyaan, bagikan pengalaman, atau diskusikan topik hobi dengan gaya obrolan yang asik.
            </p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('threads.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Judul Utas / Pertanyaan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Rekomendasi Earphone Gaming di Bawah 200rb yang Mic-nya Jernih?"
                    class="w-full px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Hobby Channel Tag -->
                <div>
                    <label for="hobby_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kanal Hobi Terkait <span class="text-rose-500">*</span>
                    </label>
                    <select name="hobby_id" id="hobby_id" required class="w-full px-4 py-3 bg-slate-50 text-xs font-semibold rounded-2xl border border-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="">-- Pilih Kanal Hobi --</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->id }}" {{ (old('hobby_id', $selectedHobby ?? '') == $hobby->id) ? 'selected' : '' }}>
                                #{{ $hobby->name }} ({{ $hobby->category }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Community / Sirkel (Optional) -->
                <div>
                    <label for="community_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tautkan ke Sirkel (Opsional)
                    </label>
                    <select name="community_id" id="community_id" class="w-full px-4 py-3 bg-slate-50 text-xs font-semibold rounded-2xl border border-slate-200 focus:outline-none focus:border-indigo-500">
                        <option value="">-- Forum Publik Sirkelku --</option>
                        @foreach($userCommunities as $comm)
                            <option value="{{ $comm->id }}" {{ (old('community_id', $selectedCommunity ?? '') == $comm->id) ? 'selected' : '' }}>
                                {{ $comm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Body -->
            <div>
                <label for="body" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Isi Pembahasan / Masalah <span class="text-rose-500">*</span>
                </label>
                <textarea name="body" id="body" rows="6" required placeholder="Jelaskan detail yang ingin kamu diskusikan atau tanyakan secara rinci..."
                    class="w-full px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 leading-relaxed">{{ old('body') }}</textarea>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-lg shadow-indigo-600/30 hover:scale-[1.01] active:scale-[0.99] transition-all">
                    Terbitkan Utas ke Tongkrongan.id &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
