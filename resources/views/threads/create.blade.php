@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">
    
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
        <a href="{{ route('threads.index') }}" class="hover:text-orange-600 flex items-center gap-1 transition-colors">
            &larr; Kembali ke Forum
        </a>
    </div>

    <div class="sk-card p-5 sm:p-6 space-y-5">
        <div class="border-b border-orange-100 pb-3.5">
            <h1 class="text-xl font-extrabold text-slate-900">
                Buat Utas di Tongkrongan.id 💬
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Tulis pertanyaan, bagikan pengalaman, atau diskusikan topik hobi secara santai.
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('threads.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Title -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Judul Utas / Pertanyaan <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Rekomendasi Earphone Gaming yang Mic-nya Jernih?"
                    class="sk-input">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Hobby Channel Tag -->
                <div>
                    <label for="hobby_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kanal Hobi Terkait <span class="text-rose-500">*</span>
                    </label>
                    <select name="hobby_id" id="hobby_id" required class="sk-input font-semibold">
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
                    <label for="community_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Tautkan ke Sirkel (Opsional)
                    </label>
                    <select name="community_id" id="community_id" class="sk-input font-semibold">
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
                <label for="body" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Isi Pembahasan <span class="text-rose-500">*</span>
                </label>
                <textarea name="body" id="body" rows="6" required placeholder="Jelaskan detail pertanyaan atau topik pembahasanmu..."
                    class="sk-input leading-relaxed">{{ old('body') }}</textarea>
            </div>

            <div class="pt-3">
                <button type="submit" class="sk-btn-primary w-full text-sm py-3">
                    Terbitkan Utas Sekarang &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
