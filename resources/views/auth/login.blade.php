<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Sirkelku - Ruang Komunitas & Teman Sefrekuensi Pelajar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-full flex items-center justify-center p-4 bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 relative overflow-hidden">
    
    <!-- Background glowing orbs -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-indigo-600/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-pink-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10 space-y-6">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 via-violet-500 to-pink-500 text-white font-black text-2xl shadow-xl shadow-indigo-500/30">
                S
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Sirkelku</h1>
            <p class="text-sm text-slate-400">Temukan teman mabar, sirkel hobi, dan tongkrongan sefrekuensi</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/10 backdrop-blur-xl border border-white/15 p-6 sm:p-8 rounded-3xl shadow-2xl text-slate-100">
            
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-500/20 border border-rose-500/30 text-rose-200 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-5 p-3.5 rounded-2xl bg-sky-500/20 border border-sky-500/30 text-sky-200 text-xs font-semibold">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">Email atau Username</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@sekolah.id atau dika_gaming"
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700/80 rounded-2xl text-white placeholder:text-slate-500 text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-300 uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full px-4 py-3 bg-slate-900/60 border border-slate-700/80 rounded-2xl text-white placeholder:text-slate-500 text-sm focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 bg-slate-800 border-slate-600 focus:ring-indigo-500">
                        <span class="text-xs text-slate-300">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-500 hover:to-violet-500 text-white font-bold text-sm rounded-2xl shadow-lg shadow-indigo-600/30 hover:scale-[1.01] active:scale-[0.99] transition-all">
                    Masuk ke Sirkelku
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/10 text-center">
                <p class="text-xs text-slate-400">
                    Belum punya akun pelajar? 
                    <a href="{{ route('register') }}" class="font-bold text-indigo-400 hover:text-indigo-300 underline underline-offset-4 ml-1">Daftar Sekarang</a>
                </p>
            </div>
        </div>

        <!-- Quick Demo Accounts (Evaluator Helpers) -->
        <div class="bg-white/5 border border-white/10 p-4 rounded-2xl text-slate-300 space-y-2">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Akses Cepat Akun Demo (1-Click)</p>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="setDemo('dika@sirkelku.id', 'password123')" class="p-2 rounded-xl bg-slate-800/80 hover:bg-indigo-600/40 text-center text-xs font-semibold border border-slate-700/50 transition-colors truncate">
                    🎮 Dika (Gamer)
                </button>
                <button type="button" onclick="setDemo('clara@sirkelku.id', 'password123')" class="p-2 rounded-xl bg-slate-800/80 hover:bg-indigo-600/40 text-center text-xs font-semibold border border-slate-700/50 transition-colors truncate">
                    🎸 Clara (Musik)
                </button>
                <button type="button" onclick="setDemo('raihan@sirkelku.id', 'password123')" class="p-2 rounded-xl bg-slate-800/80 hover:bg-indigo-600/40 text-center text-xs font-semibold border border-slate-700/50 transition-colors truncate">
                    💻 Raihan (Dev)
                </button>
            </div>
        </div>
    </div>

    <script>
        function setDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
