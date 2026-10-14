@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto p-4 sm:p-5" x-data="{ schoolType: '{{ $user->school_id ? 'existing' : 'custom' }}' }">
    
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
        <a href="{{ route('profile.show', $user->username) }}" class="hover:text-[#FF4500] flex items-center gap-1.5 transition-colors">
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
                        class="block w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-[#FF4500] hover:file:bg-emerald-100 cursor-pointer">
                </div>
            </div>

            <!-- School Section -->
            <div class="space-y-2.5 pt-3 border-t border-slate-200">
                <label class="block text-xs font-bold text-slate-700">
                    Asal Sekolah
                </label>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="schoolType = 'existing'" 
                        :class="schoolType === 'existing' ? 'bg-[#FF4500] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-[#FF4500]'"
                        class="py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-center">
                        Pilih Sekolah Terdaftar
                    </button>
                    <button type="button" @click="schoolType = 'custom'" 
                        :class="schoolType === 'custom' ? 'bg-[#FF4500] text-white shadow-sm' : 'bg-slate-100 text-slate-700 hover:bg-emerald-50 hover:text-[#FF4500]'"
                        class="py-2.5 px-3 rounded-xl text-xs font-bold transition-all text-center">
                        Tambah Sekolah Baru
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
                                        <div class="px-3 py-1.5 rounded-xl text-xs font-semibold border border-slate-200 bg-slate-50 text-slate-700 transition-all peer-checked:bg-[#FF4500] peer-checked:border-[#FF4500] peer-checked:text-white peer-checked:shadow-xs">
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- 1. Form Kredensial Akun (Username & Email) -->
    <div class="sk-card space-y-5">
        <div class="border-b border-slate-200 pb-3">
            <h2 class="text-base sm:text-lg font-black text-slate-900">
                Keamanan & Kredensial Akun
            </h2>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Perbarui username dan alamat email akunmu</p>
        </div>

        <form action="{{ route('profile.credentials.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-bold text-slate-700 mb-1">
                    Username <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 font-bold text-xs select-none">@</span>
                    <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}" required
                        class="sk-input pl-7">
                </div>
                @error('username')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1">
                    Email <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="sk-input">
                @error('email')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-3 border-t border-slate-200">
                <button type="submit" class="sk-btn-primary w-full py-3 px-4 text-xs font-bold">
                    Simpan Kredensial
                </button>
            </div>
        </form>
    </div>

    <!-- 2. Form Ganti Password -->
    <div class="sk-card space-y-5">
        <div class="border-b border-slate-200 pb-3">
            <h2 class="text-base sm:text-lg font-black text-slate-900">
                Ganti Kata Sandi
            </h2>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Pastikan akunmu menggunakan kata sandi yang aman</p>
        </div>

        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1">
                    Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="current_password" id="current_password" required placeholder="Masukkan kata sandi lama"
                    class="sk-input">
                @error('current_password')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 mb-1">
                    Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="password" id="password" required placeholder="Minimal 8 karakter"
                    class="sk-input">
                @error('password')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">
                    Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi kata sandi baru"
                    class="sk-input">
            </div>

            <div class="pt-3 border-t border-slate-200">
                <button type="submit" class="sk-btn-primary w-full py-3 px-4 text-xs font-bold">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

    <!-- 3. Zona Bahaya: Hapus Akun -->
    <div class="sk-card space-y-4 border-red-200/80 bg-red-50/20" x-data="{ showDeleteModal: {{ $errors->userDeletion->any() ? 'true' : 'false' }} }">
        <div class="border-b border-red-100 pb-3">
            <h2 class="text-base sm:text-lg font-black text-rose-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Hapus Akun
            </h2>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Tindakan ini permanen. Semua data profil, postingan, dan riwayat sirkelmu akan dihapus selamanya.</p>
        </div>

        <div class="pt-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-rose-600 font-medium leading-relaxed">
                Harap berhati-hati. Setelah akun dihapus, tidak ada cara untuk memulihkannya kembali.
            </p>
            <button type="button" @click="showDeleteModal = true"
                class="shrink-0 bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition rounded-xl py-3 px-6 font-semibold text-xs text-center cursor-pointer">
                Hapus Akun Saya
            </button>
        </div>

        <!-- Modal Konfirmasi (Popup Pengaman) -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div x-show="showDeleteModal" 
                x-transition:enter="ease-out duration-200" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-150" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
                @click="showDeleteModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showDeleteModal" 
                    x-transition:enter="ease-out duration-200" 
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave="ease-in duration-150" 
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 p-6 space-y-4">
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900" id="modal-title">Konfirmasi Penghapusan Akun</h3>
                            <p class="text-xs text-slate-500 font-medium">Apakah kamu yakin ingin menghapus akun ini?</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed font-normal bg-rose-50 border border-rose-100 p-3 rounded-xl">
                        Tindakan ini <strong class="text-rose-700">permanen</strong>. Semua data profil, postingan, dan riwayat sirkelmu akan dihapus selamanya.
                    </p>

                    <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-3.5">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label for="delete_password" class="block text-xs font-bold text-slate-700 mb-1">
                                Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" id="delete_password" required placeholder="Masukkan kata sandi untuk konfirmasi"
                                class="sk-input">
                            @if($errors->userDeletion->has('password_delete'))
                                <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $errors->userDeletion->first('password_delete') }}</p>
                            @endif
                            @error('password')
                                <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button type="button" @click="showDeleteModal = false"
                                class="py-2.5 px-4 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit"
                                class="py-2.5 px-5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-xs transition-colors cursor-pointer">
                                Hapus Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

