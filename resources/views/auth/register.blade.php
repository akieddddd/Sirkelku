<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Sirkelku</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-full flex items-center justify-center p-4 bg-zinc-100 text-zinc-900">

    <div class="max-w-md w-full space-y-5 my-8">
        
        <!-- Brand Header -->
        <div class="text-center space-y-1.5">
            <div class="inline-flex items-center justify-center w-11 h-11 rounded-lg bg-zinc-900 text-white font-black text-xl shadow-xs">
                S
            </div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-950 tracking-tight">Daftar Akun Sirkelku</h1>
            <p class="text-xs text-zinc-500">Gabung dan temukan teman sekolah & sirkel hobimu</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white border border-zinc-200 p-6 sm:p-7 rounded-xl shadow-sm space-y-4">
            
            @if($errors->any())
                <div class="p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-3.5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-zinc-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 placeholder:text-zinc-400 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400 transition-colors">
                </div>

                <div>
                    <label for="username" class="block text-xs font-semibold text-zinc-700 mb-1">Username</label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-2.5 text-zinc-400 text-xs font-medium">@</span>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="budisnt"
                            class="w-full pl-8 pr-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 placeholder:text-zinc-400 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400 transition-colors">
                    </div>
                    <p class="text-[11px] text-zinc-400 mt-1">Hanya huruf, angka, dan underscore (_).</p>
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="budi@gmail.com"
                        class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 placeholder:text-zinc-400 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400 transition-colors">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-zinc-700 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 placeholder:text-zinc-400 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400 transition-colors">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-zinc-700 mb-1">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                        class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 placeholder:text-zinc-400 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400 transition-colors">
                </div>

                <div class="pt-1">
                    <button type="submit" class="w-full py-2.5 px-4 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs rounded-lg transition-colors shadow-sm">
                        Lanjut ke Onboarding &rarr;
                    </button>
                </div>
            </form>

            <div class="pt-3 border-t border-zinc-100 text-center">
                <p class="text-xs text-zinc-500">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-semibold text-zinc-950 hover:underline ml-1">Masuk Saja</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
