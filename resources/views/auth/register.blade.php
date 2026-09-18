<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
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
<body class="min-h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 relative overflow-hidden">
    
    <!-- Background glowing orbs -->
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-violet-600/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-pink-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10 space-y-6 my-8">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 via-violet-500 to-pink-500 text-white font-black text-2xl shadow-xl shadow-indigo-500/30">
                S
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Gabung ke Sirkelku</h1>
            <p class="text-sm text-slate-400">Buat akun untuk terhubung dengan teman sekolah & hobi</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/15 p-6 sm:p-8 rounded-3xl shadow-2xl text-slate-100">
            
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/20 border border-rose-500/30 text-rose-200 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Nama Lengkap / Panggilan</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700/80 rounded-2xl text-white placeholder:text-slate-500 text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                </div>

                <div>
                    <label for="username" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Username Unik</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-slate-500 text-sm font-semibold">@</span>
                        <input type="text" id="username" name="username" value="{{ old('username') }}" required placeholder="budisnt"
                            class="w-full pl-8 pr-4 py-3 bg-slate-900/60 border border-slate-700/80 rounded-2xl text-white placeholder:text-slate-500 text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Hanya huruf, angka, dan underscore (_).</p>
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="budi@gmail.com"
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700/80 rounded-2xl text-white placeholder:text-slate-500 text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Kata Sandi</label>
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700/80 rounded-2xl text-white placeholder:text-slate-500 text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Konfirmasi Kata Sandi</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700/80 rounded-2xl text-white placeholder:text-slate-500 text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 hover:scale-[1.01] active:scale-[0.99] transition-all">
                        Lanjut ke Onboarding Profil &rarr;
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-white/10 text-center">
                <p class="text-xs text-slate-400">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 underline underline-offset-4 ml-1">Masuk Saja</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
