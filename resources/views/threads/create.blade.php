@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">
    
    <div class="flex items-center gap-2 text-xs font-medium text-zinc-500">
        <a href="{{ route('threads.index') }}" class="hover:text-zinc-950 flex items-center gap-1">
            &larr; Kembali ke Forum
        </a>
    </div>

    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm border border-zinc-200 space-y-5">
        <div class="border-b border-zinc-100 pb-3">
            <h1 class="text-lg sm:text-xl font-bold text-zinc-950">
                Buat Utas di Tongkrongan.id
            </h1>
            <p class="text-xs text-zinc-500 mt-0.5">
                Tulis pertanyaan, bagikan pengalaman, atau diskusikan topik hobi secara santai.
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('threads.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-semibold text-zinc-700 mb-1">
                    Judul Utas / Pertanyaan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Rekomendasi Earphone Gaming yang Mic-nya Jernih?"
                    class="w-full px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Hobby Channel Tag -->
                <div>
                    <label for="hobby_id" class="block text-xs font-semibold text-zinc-700 mb-1">
                        Kanal Hobi Terkait <span class="text-rose-500">*</span>
                    </label>
                    <select name="hobby_id" id="hobby_id" required class="w-full px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
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
                    <label for="community_id" class="block text-xs font-semibold text-zinc-700 mb-1">
                        Tautkan ke Sirkel (Opsional)
                    </label>
                    <select name="community_id" id="community_id" class="w-full px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
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
                <label for="body" class="block text-xs font-semibold text-zinc-700 mb-1">
                    Isi Pembahasan <span class="text-rose-500">*</span>
                </label>
                <textarea name="body" id="body" rows="6" required placeholder="Jelaskan detail pertanyaan atau topik pembahasanmu..."
                    class="w-full px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400 leading-relaxed">{{ old('body') }}</textarea>
            </div>

            <div class="pt-3">
                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shadow-sm">
                    Terbitkan Utas &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
