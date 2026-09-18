@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto" x-data="{ schoolType: '{{ $user->school_id ? 'existing' : 'custom' }}' }">
    
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
        <a href="{{ route('profile.show', $user->username) }}" class="hover:text-indigo-600 flex items-center gap-1">
            &larr; Kembali ke Profil Saya
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
        <div>
            <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-extrabold uppercase tracking-wider">
                Pengaturan Profil
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2">
                Perbarui Profil Pelajarmu
            </h1>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500">
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Bio Singkat
                </label>
                <textarea name="bio" id="bio" rows="3" placeholder="Ceritakan ketertarikanmu..."
                    class="w-full px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Ganti Foto Avatar (Maks 2 MB)
                </label>
                <div class="flex items-center gap-4">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-2xl object-cover ring-2 ring-slate-100">
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
            </div>

            <!-- School Section -->
            <div class="space-y-3 pt-2 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Asal Sekolah
                </label>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="schoolType = 'existing'" 
                        :class="schoolType === 'existing' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600'"
                        class="p-2.5 rounded-xl text-xs font-bold transition-all text-center">
                        Pilih Sekolah Terdaftar
                    </button>
                    <button type="button" @click="schoolType = 'custom'" 
                        :class="schoolType === 'custom' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-600'"
                        class="p-2.5 rounded-xl text-xs font-bold transition-all text-center">
                        Tambah Sekolah Baru
                    </button>
                </div>

                <input type="hidden" name="school_type" :value="schoolType">

                <div x-show="schoolType === 'existing'" class="space-y-1">
                    <select name="school_id" class="w-full px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200">
                        <option value="">-- Pilih Sekolah --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }} ({{ $school->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div x-show="schoolType === 'custom'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <input type="text" name="new_school_name" placeholder="Nama Sekolah Baru" class="px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200">
                    <input type="text" name="new_school_city" placeholder="Kota Sekolah" class="px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200">
                </div>
            </div>

            <!-- Hobbies Selection -->
            <div class="space-y-3 pt-2 border-t border-slate-100">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Pilihan Hobi & Minat (Minimal 1)
                </label>
                @php $userHobbyIds = $user->hobbies->pluck('id')->toArray(); @endphp
                <div class="space-y-4">
                    @foreach($hobbiesByCategory as $category => $hobbies)
                        <div>
                            <h4 class="text-[11px] font-extrabold text-slate-400 uppercase mb-2">{{ $category }}</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($hobbies as $hobby)
                                    <label class="cursor-pointer select-none">
                                        <input type="checkbox" name="hobby_ids[]" value="{{ $hobby->id }}" class="peer sr-only"
                                            {{ in_array($hobby->id, old('hobby_ids', $userHobbyIds)) ? 'checked' : '' }}>
                                        <div class="px-3.5 py-1.5 rounded-xl text-xs font-bold border border-slate-200 bg-slate-50 text-slate-600 transition-all peer-checked:bg-indigo-600 peer-checked:border-indigo-600 peer-checked:text-white peer-checked:shadow-sm">
                                            {{ $hobby->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-md shadow-indigo-600/30 transition-all">
                    Simpan Perubahan Profil &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
