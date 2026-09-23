@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">
    
    <!-- Breadcrumb / Back button -->
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
        <a href="{{ route('communities.index') }}" class="hover:text-[#588157] flex items-center gap-1 transition-colors">
            &larr; Kembali ke Direktori
        </a>
    </div>

    <div class="sk-card p-5 sm:p-6 space-y-5">
        <div class="border-b border-slate-200 pb-4">
            <h1 class="text-xl font-extrabold text-slate-800">
                Bikin Sirkel Komunitas
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Kumpulkan teman-teman sefrekuensi dari sekolahmu atau gabungkan pelajar lintas sekolah.
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Sirkel / Komunitas <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Sirkel Valorant Pelajar Bandung"
                    class="sk-input">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1.5">
                    Deskripsi Sirkel <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" id="description" rows="3" required placeholder="Jelaskan tujuan sirkel, kegiatan rutin, atau aturan kumpul..."
                    class="sk-input leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Hobby Category -->
                <div>
                    <label for="hobby_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Kategori Hobi Utama
                    </label>
                    <select name="hobby_id" id="hobby_id" class="sk-input font-semibold">
                        <option value="">-- Pilih Hobi (Opsional) --</option>
                        @foreach($hobbies as $hobby)
                            <option value="{{ $hobby->id }}" {{ old('hobby_id') == $hobby->id ? 'selected' : '' }}>
                                {{ $hobby->name }} ({{ $hobby->category }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- School Basis -->
                <div>
                    <label for="school_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Basis Sekolah (Jika Ada)
                    </label>
                    <select name="school_id" id="school_id" class="sk-input font-semibold">
                        <option value="">-- Terbuka Lintas Sekolah --</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name }} ({{ $school->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Image Uploads -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                <div>
                    <label for="banner" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Banner Sampul Sirkel
                    </label>
                    <input type="file" name="banner" id="banner" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-2.5 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF0EA] file:text-[#2D472C] hover:file:bg-[#DFEADF] cursor-pointer">
                </div>

                <div>
                    <label for="avatar" class="block text-xs font-bold text-slate-700 mb-1.5">
                        Logo / Avatar Sirkel
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-2.5 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF0EA] file:text-[#2D472C] hover:file:bg-[#DFEADF] cursor-pointer">
                </div>
            </div>

            <div class="pt-3">
                <button type="submit" class="sk-btn-primary w-full text-sm py-3">
                    Terbitkan Sirkel & Jadi Ketua Sirkel &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
