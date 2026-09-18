@extends('layouts.app')

@section('content')
<div class="space-y-5 max-w-2xl mx-auto">
    
    <!-- Breadcrumb / Back button -->
    <div class="flex items-center gap-2 text-xs font-medium text-zinc-500">
        <a href="{{ route('communities.index') }}" class="hover:text-zinc-950 flex items-center gap-1">
            &larr; Kembali ke Direktori
        </a>
    </div>

    <div class="bg-white rounded-xl p-5 sm:p-6 shadow-sm border border-zinc-200 space-y-5">
        <div class="border-b border-zinc-100 pb-4">
            <h1 class="text-lg sm:text-xl font-bold text-zinc-950">
                Bikin Sirkel Komunitas
            </h1>
            <p class="text-xs text-zinc-500 mt-0.5">
                Kumpulkan teman-teman sefrekuensi dari sekolahmu atau gabungkan pelajar lintas sekolah.
            </p>
        </div>

        @if($errors->any())
            <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-zinc-700 mb-1">
                    Nama Sirkel / Komunitas <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Sirkel Valorant Pelajar Bandung"
                    class="w-full px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-zinc-700 mb-1">
                    Deskripsi Sirkel <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" id="description" rows="3" required placeholder="Jelaskan tujuan sirkel, kegiatan rutin, atau aturan kumpul..."
                    class="w-full px-3.5 py-2.5 bg-zinc-50 text-xs sm:text-sm rounded-lg border border-zinc-200 focus:bg-white focus:outline-none focus:border-zinc-400 leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <!-- Hobby Category -->
                <div>
                    <label for="hobby_id" class="block text-xs font-semibold text-zinc-700 mb-1">
                        Kategori Hobi Utama
                    </label>
                    <select name="hobby_id" id="hobby_id" class="w-full px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
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
                    <label for="school_id" class="block text-xs font-semibold text-zinc-700 mb-1">
                        Basis Sekolah (Jika Ada)
                    </label>
                    <select name="school_id" id="school_id" class="w-full px-3 py-2 bg-zinc-50 text-xs font-medium rounded-lg border border-zinc-200 focus:outline-none focus:border-zinc-400 text-zinc-700">
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
                    <label for="banner" class="block text-xs font-semibold text-zinc-700 mb-1">
                        Banner Sampul Sirkel
                    </label>
                    <input type="file" name="banner" id="banner" accept="image/*"
                        class="block w-full text-xs text-zinc-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200">
                </div>

                <div>
                    <label for="avatar" class="block text-xs font-semibold text-zinc-700 mb-1">
                        Logo / Avatar Sirkel
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full text-xs text-zinc-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200">
                </div>
            </div>

            <div class="pt-3">
                <button type="submit" class="w-full py-2.5 px-4 rounded-lg bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs transition-colors shadow-sm">
                    Terbitkan Sirkel & Jadi Ketua Sirkel &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
