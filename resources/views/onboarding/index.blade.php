<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding Profil Pelajar - Sirkelku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; } [x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-full bg-zinc-100 text-zinc-900 py-10 px-4">
    
    <div class="max-w-2xl mx-auto space-y-6" x-data="{ schoolType: 'existing' }">
        
        <!-- Header -->
        <div class="text-center space-y-1.5">
            <div class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-zinc-900 text-white font-black text-xl shadow-xs">
                S
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-950 tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h1>
            <p class="text-xs text-zinc-500 max-w-md mx-auto">Lengkapi data sekolah & hobimu agar kami bisa menghubungkanmu dengan teman dan sirkel yang sefrekuensi.</p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white border border-zinc-200 p-6 sm:p-7 rounded-xl shadow-sm space-y-6">
            <form action="{{ route('onboarding.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Section 1: Asal Sekolah -->
                <div class="space-y-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-md bg-zinc-900 text-white font-bold text-xs flex items-center justify-center">1</span>
                        <h2 class="text-sm font-bold text-zinc-950">Asal Sekolah <span class="text-rose-500">*</span></h2>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" @click="schoolType = 'existing'" 
                            :class="schoolType === 'existing' ? 'bg-zinc-900 text-white' : 'bg-zinc-50 border border-zinc-200 text-zinc-700 hover:bg-zinc-100'"
                            class="p-2.5 rounded-lg text-xs font-semibold transition-colors text-center">
                            Pilih Sekolah Terdaftar
                        </button>
                        <button type="button" @click="schoolType = 'custom'" 
                            :class="schoolType === 'custom' ? 'bg-zinc-900 text-white' : 'bg-zinc-50 border border-zinc-200 text-zinc-700 hover:bg-zinc-100'"
                            class="p-2.5 rounded-lg text-xs font-semibold transition-colors text-center">
                            Tambah Sekolah Baru
                        </button>
                    </div>

                    <input type="hidden" name="school_type" :value="schoolType">

                    <!-- Existing School Dropdown -->
                    <div x-show="schoolType === 'existing'" class="space-y-1">
                        <label for="school_id" class="block text-xs font-medium text-zinc-500">Pilih sekolahmu:</label>
                        <select name="school_id" id="school_id" class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400">
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
                            <label for="new_school_name" class="block text-xs font-medium text-zinc-500 mb-1">Nama Sekolah Baru</label>
                            <input type="text" name="new_school_name" id="new_school_name" value="{{ old('new_school_name') }}" placeholder="Contoh: SMAN 2 Bandung"
                                class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400">
                        </div>
                        <div>
                            <label for="new_school_city" class="block text-xs font-medium text-zinc-500 mb-1">Kota Asal Sekolah</label>
                            <input type="text" name="new_school_city" id="new_school_city" value="{{ old('new_school_city') }}" placeholder="Contoh: Bandung"
                                class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400">
                        </div>
                    </div>
                </div>

                <hr class="border-zinc-100">

                <!-- Section 2: Pilih Hobi & Minat (Minimal 1) -->
                <div class="space-y-3.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-md bg-zinc-900 text-white font-bold text-xs flex items-center justify-center">2</span>
                            <h2 class="text-sm font-bold text-zinc-950">Hobi & Minat Utama <span class="text-rose-500">*</span></h2>
                        </div>
                        <span class="text-xs text-zinc-400 font-medium">Pilih minimal 1 hobi</span>
                    </div>

                    <div class="space-y-3">
                        @foreach($hobbiesByCategory as $category => $hobbies)
                            <div>
                                <h3 class="text-[11px] font-semibold uppercase tracking-wider text-zinc-400 mb-1.5">
                                    {{ $category }}
                                </h3>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($hobbies as $hobby)
                                        <label class="cursor-pointer select-none">
                                            <input type="checkbox" name="hobby_ids[]" value="{{ $hobby->id }}" class="peer sr-only"
                                                {{ (is_array(old('hobby_ids')) && in_array($hobby->id, old('hobby_ids'))) ? 'checked' : '' }}>
                                            <div class="px-3 py-1.5 rounded-lg text-xs font-medium border border-zinc-200 bg-zinc-50 text-zinc-700 transition-colors peer-checked:bg-zinc-900 peer-checked:border-zinc-900 peer-checked:text-white hover:bg-zinc-100">
                                                {{ $hobby->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-zinc-100">

                <!-- Section 3: Bio & Foto Profil (Opsional) -->
                <div class="space-y-3.5">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-md bg-zinc-900 text-white font-bold text-xs flex items-center justify-center">3</span>
                        <h2 class="text-sm font-bold text-zinc-950">Bio & Foto Profil <span class="text-xs font-normal text-zinc-400">(Opsional)</span></h2>
                    </div>

                    <div>
                        <label for="bio" class="block text-xs font-medium text-zinc-500 mb-1">Bio Singkat</label>
                        <textarea name="bio" id="bio" rows="2" placeholder="Ceritakan sedikit tentang dirimu atau rank game kesukaan..."
                            class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400">{{ old('bio') }}</textarea>
                    </div>

                    <div>
                        <label for="avatar" class="block text-xs font-medium text-zinc-500 mb-1">Foto Avatar</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                            class="block w-full text-xs text-zinc-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 cursor-pointer">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs rounded-lg transition-colors shadow-sm">
                        Simpan & Masuk ke Sirkelku &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
