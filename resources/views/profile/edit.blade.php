@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto" x-data="{ schoolType: '{{ $user->school_id ? 'existing' : 'custom' }}' }">
    
    <div class="flex items-center gap-2 text-xs font-medium text-zinc-500">
        <a href="{{ route('profile.show', $user->username) }}" class="hover:text-zinc-950 flex items-center gap-1">
            &larr; Kembali ke Profil Saya
        </a>
    </div>

    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm border border-zinc-200 space-y-5">
        <div class="border-b border-zinc-100 pb-3">
            <h1 class="text-lg sm:text-xl font-bold text-zinc-950">
                Pengaturan Profil
            </h1>
            <p class="text-xs text-zinc-500 mt-0.5">Perbarui informasi profil dan preferensi minatmu</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-zinc-700 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400">
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-xs font-semibold text-zinc-700 mb-1">
                    Bio Singkat
                </label>
                <textarea name="bio" id="bio" rows="3" placeholder="Ceritakan sedikit tentang dirimu atau minatmu..."
                    class="w-full px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400 leading-relaxed">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-xs font-semibold text-zinc-700 mb-1">
                    Foto Avatar
                </label>
                <div class="flex items-center gap-3.5">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover ring-1 ring-zinc-200">
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full text-xs text-zinc-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200">
                </div>
            </div>

            <!-- School Section -->
            <div class="space-y-2.5 pt-2 border-t border-zinc-100">
                <label class="block text-xs font-semibold text-zinc-700">
                    Asal Sekolah
                </label>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="schoolType = 'existing'" 
                        :class="schoolType === 'existing' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'"
                        class="py-2 px-3 rounded-lg text-xs font-semibold transition-colors text-center">
                        Pilih Sekolah Terdaftar
                    </button>
                    <button type="button" @click="schoolType = 'custom'" 
                        :class="schoolType === 'custom' ? 'bg-zinc-900 text-white' : 'bg-zinc-100 text-zinc-700 hover:bg-zinc-200'"
                        class="py-2 px-3 rounded-lg text-xs font-semibold transition-colors text-center">
                        Tambah Sekolah Baru
                    </button>
                </div>

                <input type="hidden" name="school_type" :value="schoolType">

                <div x-show="schoolType === 'existing'" class="space-y-1">
                    <select name="school_id" class="w-full px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400">
                        <option value="">-- Pilih Sekolah --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }} ({{ $school->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div x-show="schoolType === 'custom'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <input type="text" name="new_school_name" placeholder="Nama Sekolah Baru" class="px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400">
                    <input type="text" name="new_school_city" placeholder="Kota Sekolah" class="px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400">
                </div>
            </div>

            <!-- Hobbies Selection -->
            <div class="space-y-2.5 pt-2 border-t border-zinc-100">
                <label class="block text-xs font-semibold text-zinc-700">
                    Pilihan Hobi & Minat
                </label>
                @php $userHobbyIds = $user->hobbies->pluck('id')->toArray(); @endphp
                <div class="space-y-3">
                    @foreach($hobbiesByCategory as $category => $hobbies)
                        <div>
                            <h4 class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider mb-1.5">{{ $category }}</h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($hobbies as $hobby)
                                    <label class="cursor-pointer select-none">
                                        <input type="checkbox" name="hobby_ids[]" value="{{ $hobby->id }}" class="peer sr-only"
                                            {{ in_array($hobby->id, old('hobby_ids', $userHobbyIds)) ? 'checked' : '' }}>
                                        <div class="px-3 py-1.5 rounded-lg text-xs font-medium border border-zinc-200 bg-zinc-50 text-zinc-700 transition-colors peer-checked:bg-zinc-900 peer-checked:border-zinc-900 peer-checked:text-white">
                                            {{ $hobby->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-zinc-100">
                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shadow-sm">
                    Simpan Perubahan Profil &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
