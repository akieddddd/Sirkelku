@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto" x-data="{ schoolType: '{{ $user->school_id ? 'existing' : 'custom' }}' }">
    
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
        <a href="{{ route('profile.show', $user->username) }}" class="hover:text-[#588157] flex items-center gap-1.5 transition-colors">
            &larr; Kembali ke Profil Saya
        </a>
    </div>

    <div class="sk-card space-y-5">
        <div class="border-b border-slate-200 pb-3">
            <h1 class="text-lg sm:text-xl font-black text-slate-900">
                Pengaturan Profil
            </h1>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Perbarui informasi profil dan preferensi minatmu</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="sk-input">
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-xs font-bold text-slate-700 mb-1">
                    Bio Singkat
                </label>
                <textarea name="bio" id="bio" rows="3" placeholder="Ceritakan sedikit tentang dirimu atau minatmu..."
                    class="sk-input leading-relaxed">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-xs font-bold text-slate-700 mb-1">
                    Foto Avatar
                </label>
                <div class="flex items-center gap-3.5">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-emerald-200">
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-[#588157] hover:file:bg-emerald-100 cursor-pointer">
                </div>
            </div>

            <!-- School Section -->
            <div class="space-y-2.5 pt-3 border-t border-slate-200">
                <label class="block text-xs font-bold text-slate-700">
                    Asal Sekolah
                </label>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="schoolType = 'existing'" 
                        :class="schoolType === 'existing' ? 'bg-[#588157] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-[#588157]'"
                        class="py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-center">
                        Pilih Sekolah Terdaftar
                    </button>
                    <button type="button" @click="schoolType = 'custom'" 
                        :class="schoolType === 'custom' ? 'bg-[#588157] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-[#588157]'"
                        class="py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-center">
                        + Tambah Sekolah Baru
                    </button>
                </div>

                <input type="hidden" name="school_type" :value="schoolType">

                <div x-show="schoolType === 'existing'" class="space-y-1">
                    <select name="school_id" class="sk-select">
                        <option value="">-- Pilih Sekolah --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }} ({{ $school->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div x-show="schoolType === 'custom'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <input type="text" name="new_school_name" placeholder="Nama Sekolah Baru" class="sk-input">
                    <input type="text" name="new_school_city" placeholder="Kota Sekolah" class="sk-input">
                </div>
            </div>

            <!-- Hobbies Selection -->
            <div class="space-y-2.5 pt-3 border-t border-slate-200">
                <label class="block text-xs font-bold text-slate-700">
                    Pilihan Hobi & Minat
                </label>
                @php $userHobbyIds = $user->hobbies->pluck('id')->toArray(); @endphp
                <div class="space-y-3">
                    @foreach($hobbiesByCategory as $category => $hobbies)
                        <div>
                            <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">{{ $category }}</h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($hobbies as $hobby)
                                    <label class="cursor-pointer select-none">
                                        <input type="checkbox" name="hobby_ids[]" value="{{ $hobby->id }}" class="peer sr-only"
                                            {{ in_array($hobby->id, old('hobby_ids', $userHobbyIds)) ? 'checked' : '' }}>
                                        <div class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 bg-slate-50 text-slate-700 transition-all peer-checked:bg-[#588157] peer-checked:border-[#588157] peer-checked:text-white peer-checked:shadow-xs">
                                            #{{ $hobby->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200">
                <button type="submit" class="sk-btn-primary w-full py-3 px-4 text-xs font-bold">
                    Simpan Perubahan Profil &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

