<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF8F5]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Sirkelku</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full flex items-center justify-center p-4 bg-[#FAF8F5] text-slate-800 antialiased selection:bg-orange-500 selection:text-white">

    <div class="max-w-md w-full space-y-6 my-8">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <a href="{{ url('/') }}" class="inline-block group">
                <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo" class="w-14 h-14 object-contain mx-auto transition-transform group-hover:scale-105 duration-200">
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Akun Sirkelku</h1>
            <p class="text-xs text-slate-500 font-medium">Gabung dan temukan teman sekolah & sirkel hobimu</p>
        </div>

        <!-- Register Card -->
        <div class="sk-card p-6 sm:p-7 space-y-5">
            
            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                        class="sk-input">
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 mb-1.5">Username</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-slate-400 text-xs font-bold">@</span>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="budisnt"
                            class="sk-input pl-8">
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">Hanya huruf, angka, dan underscore (_).</p>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="budi@gmail.com"
                        class="sk-input">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                        class="sk-input">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                        class="sk-input">
                </div>

                <div class="pt-2">
                    <button type="submit" class="sk-btn-primary w-full text-sm py-3">
                        Lanjut ke Onboarding &rarr;
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-orange-100 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-orange-600 hover:text-orange-700 hover:underline ml-1">Masuk Saja</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
