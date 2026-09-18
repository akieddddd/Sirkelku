<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
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
<body class="min-h-full bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-slate-100 py-10 px-4">
    
    <div class="max-w-2xl mx-auto space-y-8" x-data="{ schoolType: 'existing', selectedCount: 0 }">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-pink-500 text-white font-black text-xl shadow-lg shadow-indigo-500/30">
                S
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Hai {{ Auth::user()->name }}! 👋</h1>
            <p class="text-sm text-slate-400">Tinggal satu langkah lagi! Lengkapi data sekolah & hobimu agar kami bisa menghubungkanmu dengan teman sefrekuensi.</p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-500/20 border border-rose-500/30 text-rose-200 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/15 p-6 sm:p-8 rounded-3xl shadow-2xl">
            <form action="{{ route('onboarding.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Section 1: Asal Sekolah -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-xl bg-indigo-500 text-white font-black text-xs flex items-center justify-center shadow-md">1</span>
                        <h2 class="text-base font-bold text-white">Asal Sekolah <span class="text-rose-400">*</span></h2>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="schoolType = 'existing'" 
                            :class="schoolType === 'existing' ? 'bg-indigo-600 border-indigo-400 text-white shadow-md' : 'bg-slate-900/60 border-slate-700 text-slate-300'"
                            class="p-3 rounded-2xl border text-xs font-bold transition-all text-center">
                            🏫 Pilih Sekolah Terdaftar
                        </button>
                        <button type="button" @click="schoolType = 'custom'" 
                            :class="schoolType === 'custom' ? 'bg-indigo-600 border-indigo-400 text-white shadow-md' : 'bg-slate-900/60 border-slate-700 text-slate-300'"
                            class="p-3 rounded-2xl border text-xs font-bold transition-all text-center">
                            ➕ Tambah Sekolah Baru
                        </button>
                    </div>

                    <input type="hidden" name="school_type" :value="schoolType">

                    <!-- Existing School Dropdown -->
                    <div x-show="schoolType === 'existing'" class="space-y-1">
                        <label for="school_id" class="block text-xs font-medium text-slate-400">Pilih nama sekolahmu:</label>
                        <select name="school_id" id="school_id" class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-2xl text-white text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20">
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
                            <label for="new_school_name" class="block text-xs font-medium text-slate-400 mb-1">Nama Sekolah Baru</label>
                            <input type="text" name="new_school_name" id="new_school_name" value="{{ old('new_school_name') }}" placeholder="Contoh: SMAN 2 Bandung"
                                class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-2xl text-white text-sm focus:outline-none focus:border-indigo-400">
                        </div>
                        <div>
                            <label for="new_school_city" class="block text-xs font-medium text-slate-400 mb-1">Kota Asal Sekolah</label>
                            <input type="text" name="new_school_city" id="new_school_city" value="{{ old('new_school_city') }}" placeholder="Contoh: Bandung"
                                class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-2xl text-white text-sm focus:outline-none focus:border-indigo-400">
                        </div>
                    </div>
                </div>

                <hr class="border-white/10">

                <!-- Section 2: Pilih Hobi & Minat (Minimal 1) -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 rounded-xl bg-violet-500 text-white font-black text-xs flex items-center justify-center shadow-md">2</span>
                            <h2 class="text-base font-bold text-white">Hobi & Minat Utama <span class="text-rose-400">*</span></h2>
                        </div>
                        <span class="text-xs text-indigo-300 font-medium">Pilih minimal 1 hobi</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($hobbiesByCategory as $category => $hobbies)
                            <div>
                                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-2">
                                    {{ $category }}
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($hobbies as $hobby)
                                        <label class="cursor-pointer select-none">
                                            <input type="checkbox" name="hobby_ids[]" value="{{ $hobby->id }}" class="peer sr-only"
                                                {{ (is_array(old('hobby_ids')) && in_array($hobby->id, old('hobby_ids'))) ? 'checked' : '' }}>
                                            <div class="px-3.5 py-2 rounded-xl text-xs font-bold border border-slate-700/80 bg-slate-900/50 text-slate-300 transition-all peer-checked:bg-gradient-to-r peer-checked:from-indigo-600 peer-checked:to-violet-600 peer-checked:border-indigo-400 peer-checked:text-white peer-checked:shadow-md hover:bg-slate-800">
                                                {{ $hobby->name }}
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-white/10">

                <!-- Section 3: Bio & Foto Profil (Opsional) -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-xl bg-pink-500 text-white font-black text-xs flex items-center justify-center shadow-md">3</span>
                        <h2 class="text-base font-bold text-white">Bio & Foto Profil <span class="text-xs font-normal text-slate-400">(Opsional)</span></h2>
                    </div>

                    <div>
                        <label for="bio" class="block text-xs font-medium text-slate-400 mb-1">Bio Singkat</label>
                        <textarea name="bio" id="bio" rows="2" placeholder="Ceritakan sedikit tentang dirimu atau rank game kesukaan..."
                            class="w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-2xl text-white text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20">{{ old('bio') }}</textarea>
                    </div>

                    <div>
                        <label for="avatar" class="block text-xs font-medium text-slate-400 mb-1">Unggah Foto Avatar (JPG/PNG/WebP, maks 2 MB)</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                            class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full py-4 px-6 bg-gradient-to-r from-indigo-600 via-violet-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500 text-white font-extrabold text-sm rounded-2xl shadow-xl shadow-indigo-600/30 hover:scale-[1.01] active:scale-[0.99] transition-all">
                        Simpan & Masuk ke Nongkrong Yuk &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
