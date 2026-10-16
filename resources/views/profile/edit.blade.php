@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto p-4 sm:p-5" x-data="{ schoolType: '{{ $user->school_id ? 'existing' : 'custom' }}' }">
    
    <!-- Breadcrumb / Back Link -->
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-zinc-400">
        <a href="{{ route('profile.show', $user->username) }}" class="hover:text-[#FF4500] dark:hover:text-[#FF5722] flex items-center gap-1.5 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Profil Saya
        </a>
    </div>

    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-xs font-semibold flex items-center gap-2 shadow-xs">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- KARTU 1: PROFIL & PREFERENSI -->
    <div class="sk-card space-y-5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs">
        <div class="border-b border-slate-200 dark:border-zinc-800 pb-3 flex items-start justify-between gap-3">
            <div>
                <span class="inline-block text-[10px] font-black uppercase tracking-wider text-[#FF4500] mb-1">Kartu 1</span>
                <h1 class="text-base sm:text-lg font-black text-slate-900 dark:text-zinc-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil & Preferensi
                </h1>
                <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-medium">Perbarui informasi identitas publik, sekolah, dan hobi sirkelmu</p>
            </div>
        </div>

        @if($errors->has('name') || $errors->has('bio') || $errors->has('avatar') || $errors->has('hobby_ids'))
            <div class="p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="sk-input">
                @error('name')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Bio / Tentang Saya
                </label>
                <textarea name="bio" id="bio" rows="3" placeholder="Ceritakan sedikit tentang dirimu atau minatmu..."
                    class="sk-input leading-relaxed">{{ old('bio', $user->bio) }}</textarea>
                @error('bio')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Avatar -->
            <div>
                <label for="avatar" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Foto Profil (Avatar)
                </label>
                <div class="flex items-center gap-3.5">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-[#FF4500]/30 shadow-xs">
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full text-xs text-slate-500 dark:text-zinc-400 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 dark:file:bg-orange-950/40 file:text-[#FF4500] hover:file:bg-orange-100 dark:hover:file:bg-orange-900/50 cursor-pointer">
                </div>
                @error('avatar')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- School Section -->
            <div class="space-y-2.5 pt-3 border-t border-slate-200 dark:border-zinc-800">
                <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300">
                    Pilihan Sekolah
                </label>

                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="schoolType = 'existing'" 
                        :class="schoolType === 'existing' ? 'bg-[#FF4500] text-white shadow-xs' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-orange-50 dark:hover:bg-zinc-700 hover:text-[#FF4500]'"
                        class="py-2 px-3 rounded-xl text-xs font-bold transition-all text-center cursor-pointer">
                        Pilih Sekolah Terdaftar
                    </button>
                    <button type="button" @click="schoolType = 'custom'" 
                        :class="schoolType === 'custom' ? 'bg-[#FF4500] text-white shadow-xs' : 'bg-slate-100 dark:bg-zinc-800 text-slate-700 dark:text-zinc-300 hover:bg-orange-50 dark:hover:bg-zinc-700 hover:text-[#FF4500]'"
                        class="py-2 px-3 rounded-xl text-xs font-bold transition-all text-center cursor-pointer">
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
            <div class="space-y-2.5 pt-3 border-t border-slate-200 dark:border-zinc-800">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700 dark:text-zinc-300">
                        Pilihan Minat & Hobi
                    </label>
                    <span class="text-[11px] text-slate-500 dark:text-zinc-400 font-medium">Pilih minimal 1</span>
                </div>
                @php $userHobbyIds = $user->hobbies->pluck('id')->toArray(); @endphp
                <div class="space-y-3">
                    @foreach($hobbiesByCategory as $category => $hobbies)
                        <div>
                            <h4 class="text-[10px] font-black text-slate-500 dark:text-zinc-400 uppercase tracking-wider mb-1.5">{{ $category }}</h4>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($hobbies as $hobby)
                                    <label class="cursor-pointer select-none">
                                        <input type="checkbox" name="hobby_ids[]" value="{{ $hobby->id }}" class="peer sr-only"
                                            {{ in_array($hobby->id, old('hobby_ids', $userHobbyIds)) ? 'checked' : '' }}>
                                        <div class="px-3 py-1.5 rounded-xl text-xs font-bold border border-slate-200/90 dark:border-zinc-700 bg-slate-50 hover:bg-orange-50/60 dark:bg-zinc-800 dark:hover:bg-zinc-700/60 text-slate-700 dark:text-zinc-300 transition-all peer-checked:bg-[#FF4500] peer-checked:border-[#FF4500] peer-checked:text-white peer-checked:shadow-xs hover:scale-[1.03]">
                                            #{{ $hobby->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800">
                <button type="submit" class="sk-btn-primary w-full py-2.5 px-4 text-xs font-bold cursor-pointer">
                    Simpan Profil & Preferensi
                </button>
            </div>
        </form>
    </div>

    <!-- KARTU 2: KREDENSIAL & PRIVASI AKUN -->
    <div class="sk-card space-y-5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs">
        <div class="border-b border-slate-200 dark:border-zinc-800 pb-3">
            <span class="inline-block text-[10px] font-black uppercase tracking-wider text-[#FF4500] mb-1">Kartu 2</span>
            <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-zinc-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Kredensial & Privasi Akun
            </h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-medium">Kelola nama pengguna, email, dan visibilitas akunmu di linimasa</p>
        </div>

        <form action="{{ route('profile.credentials.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Username -->
            <div>
                <label for="username" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Username <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 dark:text-zinc-400 font-bold text-xs select-none">@</span>
                    <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}" required
                        class="sk-input pl-7">
                </div>
                @error('username')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                    class="sk-input">
                @error('email')
                    <p class="mt-1 text-[11px] font-semibold text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Privacy Toggle Switch -->
            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800" x-data="{ isPrivate: {{ old('is_private', $user->is_private) ? 'true' : 'false' }} }">
                <div class="flex items-start justify-between gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-zinc-800/60 border border-slate-200 dark:border-zinc-700/60">
                    <div class="space-y-1 pr-2">
                        <div class="flex items-center gap-2">
                            <label for="is_private" class="text-xs font-bold text-slate-800 dark:text-zinc-200 cursor-pointer">
                                Status Privasi Akun:
                            </label>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-black transition-colors"
                                :class="isPrivate ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-400 border border-amber-200 dark:border-amber-800' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800'"
                                x-text="isPrivate ? 'Privat' : 'Publik'">
                                {{ old('is_private', $user->is_private) ? 'Privat' : 'Publik' }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed font-normal">
                            Saat akun privat aktif, hanya teman yang disetujui yang dapat melihat cerita dan kirimanmu. Linimasa tamu dan orang yang belum berteman tidak dapat melihat postinganmu.
                        </p>
                    </div>

                    <!-- Toggle Switch Element -->
                    <label class="relative inline-flex items-center cursor-pointer shrink-0 mt-1">
                        <input type="hidden" name="is_private" value="0">
                        <input type="checkbox" name="is_private" id="is_private" value="1"
                            class="sr-only peer"
                            {{ old('is_private', $user->is_private) ? 'checked' : '' }}
                            :checked="isPrivate"
                            @change="isPrivate = $event.target.checked">
                        <div class="w-11 h-6 bg-slate-300 dark:bg-zinc-700 rounded-full peer peer-focus:outline-hidden peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-[#FF4500]"></div>
                    </label>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800">
                <button type="submit" class="sk-btn-primary w-full py-2.5 px-4 text-xs font-bold cursor-pointer">
                    Simpan Kredensial & Privasi
                </button>
            </div>
        </form>
    </div>

    <!-- KARTU 3: GANTI KATA SANDI -->
    <div class="sk-card space-y-5 bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs">
        <div class="border-b border-slate-200 dark:border-zinc-800 pb-3">
            <span class="inline-block text-[10px] font-black uppercase tracking-wider text-[#FF4500] mb-1">Kartu 3</span>
            <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-zinc-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Ganti Kata Sandi
            </h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-medium">Pastikan akunmu terlindungi dengan kata sandi yang kuat dan aman</p>
        </div>

        <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
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
                <label for="password" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
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
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                    Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Ulangi kata sandi baru"
                    class="sk-input">
            </div>

            <div class="pt-3 border-t border-slate-200 dark:border-zinc-800">
                <button type="submit" class="sk-btn-primary w-full py-2.5 px-4 text-xs font-bold cursor-pointer">
                    Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

    <!-- KARTU 4: ZONA BAHAYA (HAPUS AKUN) -->
    <div class="sk-card space-y-4 border border-rose-200 dark:border-rose-900/50 bg-rose-50/30 dark:bg-rose-950/20 rounded-2xl p-5 shadow-xs" x-data="{ showDeleteModal: {{ $errors->userDeletion->any() ? 'true' : 'false' }} }">
        <div class="border-b border-rose-200/60 dark:border-rose-900/40 pb-3">
            <span class="inline-block text-[10px] font-black uppercase tracking-wider text-rose-600 dark:text-rose-400 mb-1">Kartu 4</span>
            <h2 class="text-base sm:text-lg font-black text-rose-700 dark:text-rose-400 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Zona Bahaya (Hapus Akun)
            </h2>
            <p class="text-xs text-slate-500 dark:text-zinc-400 mt-0.5 font-medium">Tindakan ini permanen. Semua data profil, postingan, dan riwayat sirkelmu akan dihapus selamanya.</p>
        </div>

        <div class="pt-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-rose-600 dark:text-rose-400 font-medium leading-relaxed">
                Harap berhati-hati. Setelah akun dihapus, kamu akan dikeluarkan dan dialihkan ke linimasa publik.
            </p>
            <button type="button" @click="showDeleteModal = true"
                class="shrink-0 bg-white dark:bg-zinc-900 text-rose-600 dark:text-rose-400 border border-rose-300 dark:border-rose-800 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white transition rounded-xl py-2.5 px-5 font-bold text-xs text-center cursor-pointer shadow-xs">
                Hapus Akun Saya
            </button>
        </div>

        <!-- Modal Konfirmasi Hapus Akun -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div x-show="showDeleteModal" 
                x-transition:enter="ease-out duration-200" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-150" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0" 
                class="fixed inset-0 bg-slate-900/60 dark:bg-black/80 backdrop-blur-xs transition-opacity" 
                @click="showDeleteModal = false"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showDeleteModal" 
                    x-transition:enter="ease-out duration-200" 
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave="ease-in duration-150" 
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                    class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200 dark:border-zinc-800 p-6 space-y-4">
                    
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-zinc-100" id="modal-title">Konfirmasi Penghapusan Akun</h3>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 font-medium">Apakah kamu yakin ingin menghapus akun ini secara permanen?</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-zinc-300 leading-relaxed font-normal bg-rose-50 dark:bg-rose-950/30 border border-rose-100 dark:border-rose-900/50 p-3 rounded-xl">
                        Tindakan ini <strong class="text-rose-700 dark:text-rose-400">tidak dapat dibatalkan</strong>. Semua postingan, komentar, dan data akunmu akan dihapus seketika.
                    </p>

                    <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-3.5">
                        @csrf
                        @method('DELETE')

                        <div>
                            <label for="delete_password" class="block text-xs font-bold text-slate-700 dark:text-zinc-300 mb-1">
                                Masukkan Kata Sandi Konfirmasi <span class="text-rose-500">*</span>
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

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-zinc-800">
                            <button type="button" @click="showDeleteModal = false"
                                class="py-2 px-4 rounded-xl text-xs font-bold text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer">
                                Batal
                            </button>
                            <button type="submit"
                                class="py-2 px-5 rounded-xl text-xs font-bold bg-rose-600 hover:bg-rose-700 text-white shadow-xs transition-colors cursor-pointer">
                                Hapus Akun Permanen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
