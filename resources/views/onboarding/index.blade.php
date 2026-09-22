<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF8F5]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Profil Pelajar - Sirkelku</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[#FAF8F5] text-slate-800 py-10 px-4 antialiased selection:bg-orange-500 selection:text-white">
    
    <div class="max-w-2xl mx-auto space-y-6" x-data="{ schoolType: 'existing' }">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo" class="w-14 h-14 object-contain mx-auto transition-transform hover:scale-105 duration-200">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-xs text-slate-500 font-medium max-w-md mx-auto leading-relaxed">Lengkapi data sekolah & hobimu agar kami bisa menghubungkanmu dengan teman dan sirkel yang sefrekuensi.</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="sk-card p-6 sm:p-7 space-y-6">
            <form action="{{ route('onboarding.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Section 1: Asal Sekolah -->
                <div class="space-y-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-orange-500 text-white font-extrabold text-xs flex items-center justify-center">1</span>
                        <h2 class="text-sm font-extrabold text-slate-900">Asal Sekolah <span class="text-rose-500">*</span></h2>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" @click="schoolType = 'existing'" 
                            :class="schoolType === 'existing' ? 'bg-orange-500 text-white shadow-xs font-bold' : 'bg-orange-50/60 border border-orange-200/80 text-slate-700 hover:bg-orange-100/60 font-semibold'"
                            class="p-2.5 rounded-xl text-xs transition-all text-center">
                            Pilih Sekolah Terdaftar
                        </button>
                        <button type="button" @click="schoolType = 'custom'" 
                            :class="schoolType === 'custom' ? 'bg-orange-500 text-white shadow-xs font-bold' : 'bg-orange-50/60 border border-orange-200/80 text-slate-700 hover:bg-orange-100/60 font-semibold'"
                            class="p-2.5 rounded-xl text-xs transition-all text-center">
                            Tambah Sekolah Baru
                        </button>
                    </div>

                    <input type="hidden" name="school_type" :value="schoolType">

                    <!-- Existing School Dropdown -->
                    <div x-show="schoolType === 'existing'" class="space-y-1">
                        <label for="school_id" class="block text-xs font-semibold text-slate-500">Pilih sekolahmu:</label>
                        <select name="school_id" id="school_id" class="sk-input">
                            <option value="">-- Pilih dari daftar sekolah --</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->school_name }} ({{ $school->city }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom School Inputs -->
                    <div x-show="schoolType === 'custom'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="new_school_name" class="block text-xs font-semibold text-slate-500 mb-1">Nama Sekolah Baru</label>
                            <input type="text" name="new_school_name" id="new_school_name" value="{{ old('new_school_name') }}" placeholder="Contoh: SMAN 2 Bandung"
                                class="sk-input">
                        </div>
                        <div>
                            <label for="new_school_city" class="block text-xs font-semibold text-slate-500 mb-1">Kota Asal Sekolah</label>
                            <input type="text" name="new_school_city" id="new_school_city" value="{{ old('new_school_city') }}" placeholder="Contoh: Bandung"
                                class="sk-input">
                        </div>
                    </div>
                </div>

                <hr class="border-orange-100">

                <!-- Section 2: Pilih Hobi & Minat (Minimal 1) -->
                <div class="space-y-3.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-lg bg-orange-500 text-white font-extrabold text-xs flex items-center justify-center">2</span>
                            <h2 class="text-sm font-extrabold text-slate-900">Hobi & Minat Utama <span class="text-rose-500">*</span></h2>
                        </div>
                        <span class="text-xs text-slate-400 font-semibold">Pilih minimal 1 hobi</span>
                    </div>

                    <div class="space-y-3.5">
                        @foreach($hobbiesByCategory as $category => $hobbies)
                            <div>
                                <h3 class="text-[11px] font-bold uppercase tracking-wider text-orange-600 mb-1.5">
                                    {{ $category }}
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($hobbies as $hobby)
                                        <label class="cursor-pointer select-none">
                                            <input type="checkbox" name="hobby_ids[]" value="{{ $hobby->id }}" class="peer sr-only"
                                                {{ (is_array(old('hobby_ids')) && in_array($hobby->id, old('hobby_ids'))) ? 'checked' : '' }}>
                                            <div class="px-3.5 py-1.5 rounded-xl text-xs font-bold border border-orange-200/80 bg-orange-50/50 text-slate-700 transition-all peer-checked:bg-orange-500 peer-checked:border-orange-500 peer-checked:text-white peer-checked:shadow-xs hover:bg-orange-100/60">
                                                {{ $hobby->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-orange-100">

                <!-- Section 3: Bio & Foto Profil (Opsional) -->
                <div class="space-y-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-lg bg-orange-500 text-white font-extrabold text-xs flex items-center justify-center">3</span>
                        <h2 class="text-sm font-extrabold text-slate-900">Bio & Foto Profil <span class="text-xs font-normal text-slate-400">(Opsional)</span></h2>
                    </div>

                    <div>
                        <label for="bio" class="block text-xs font-semibold text-slate-500 mb-1">Bio Singkat</label>
                        <textarea name="bio" id="bio" rows="2" placeholder="Ceritakan sedikit tentang dirimu atau rank game kesukaan..."
                            class="sk-input">{{ old('bio') }}</textarea>
                    </div>

                    <div>
                        <label for="avatar" class="block text-xs font-semibold text-slate-500 mb-1">Foto Avatar</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                            class="block w-full text-xs text-slate-500 file:mr-2.5 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 cursor-pointer">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="sk-btn-primary w-full text-sm py-3">
                        Simpan & Masuk ke Sirkelku &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
