@extends('layouts.app')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">
    
    <!-- Breadcrumb / Back button -->
    <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
        <a href="{{ route('communities.index') }}" class="hover:text-indigo-600 flex items-center gap-1">
            &larr; Kembali ke Direktori
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 space-y-6">
        <div>
            <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[11px] font-extrabold uppercase tracking-wider">
                Buat Komunitas Baru
            </span>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 mt-2">
                Bikin Sirkelmu Sendiri
            </h1>
            <p class="text-xs text-slate-500 mt-1">
                Kumpulkan teman-teman sefrekuensi dari sekolahmu atau gabungkan pelajar dari berbagai sekolah.
            </p>
        </div>

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('communities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Sirkel / Komunitas <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Sirkel Valorant Pelajar Bandung"
                    class="w-full px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Deskripsi Sirkel <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" id="description" rows="3" required placeholder="Jelaskan tujuan sirkel, kegiatan rutin, atau aturan kumpul..."
                    class="w-full px-4 py-3 bg-slate-50 text-sm rounded-2xl border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Hobby Category -->
                <div>
                    <label for="hobby_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kategori Hobi Utama
                    </label>
                    <select name="hobby_id" id="hobby_id" class="w-full px-4 py-3 bg-slate-50 text-xs font-semibold rounded-2xl border border-slate-200 focus:outline-none focus:border-indigo-500">
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
                    <label for="school_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Basis Sekolah (Jika Ada)
                    </label>
                    <select name="school_id" id="school_id" class="w-full px-4 py-3 bg-slate-50 text-xs font-semibold rounded-2xl border border-slate-200 focus:outline-none focus:border-indigo-500">
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="banner" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Banner Sampul Sirkel
                    </label>
                    <input type="file" name="banner" id="banner" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>

                <div>
                    <label for="avatar" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Logo / Avatar Sirkel
                    </label>
                    <input type="file" name="avatar" id="avatar" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-lg shadow-indigo-600/30 hover:scale-[1.01] active:scale-[0.99] transition-all">
                    Terbitkan Sirkel & Jadi Ketua Sirkel &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
