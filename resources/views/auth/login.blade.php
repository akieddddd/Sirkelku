<!DOCTYPE html>
<html lang="id" class="h-full bg-zinc-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Sirkelku - Ruang Komunitas & Teman Sefrekuensi Pelajar</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="min-h-full flex items-center justify-center p-4 bg-zinc-100 text-zinc-900">

    <div class="max-w-md w-full space-y-5 my-8">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <a href="{{ url('/') }}" class="inline-block group">
                <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo" class="w-14 h-14 object-contain mx-auto transition-transform group-hover:scale-105 duration-200 drop-shadow-xs">
            </a>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-950 tracking-tight">Masuk ke Sirkelku</h1>
            <p class="text-xs text-zinc-500">Temukan teman mabar, sirkel hobi, dan tongkrongan pelajar</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white border border-zinc-200 p-6 sm:p-7 rounded-xl shadow-sm space-y-4">
            
            @if($errors->any())
                <div class="p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('info'))
                <div class="p-3 rounded-lg bg-zinc-50 border border-zinc-200 text-zinc-800 text-xs font-medium">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-3.5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-zinc-700 mb-1">Email atau Username</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@sekolah.id atau username"
                        class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 placeholder:text-zinc-400 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400 transition-colors">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-zinc-700">Kata Sandi</label>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 bg-zinc-50 border border-zinc-200 rounded-lg text-zinc-900 placeholder:text-zinc-400 text-xs sm:text-sm focus:bg-white focus:outline-none focus:border-zinc-400 transition-colors">
                </div>

                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded text-zinc-900 bg-white border-zinc-300 focus:ring-zinc-900">
                        <span class="text-xs text-zinc-600">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-zinc-900 hover:bg-zinc-800 text-white font-semibold text-xs rounded-lg transition-colors shadow-sm">
                    Masuk
                </button>
            </form>

            <div class="pt-3 border-t border-zinc-100 text-center">
                <p class="text-xs text-zinc-500">
                    Belum punya akun pelajar? 
                    <a href="{{ route('register') }}" class="font-semibold text-zinc-950 hover:underline ml-1">Daftar Sekarang</a>
                </p>
            </div>
        </div>

        <!-- Quick Demo Accounts -->
        <div class="bg-white border border-zinc-200 p-4 rounded-xl text-zinc-700 space-y-2.5 shadow-sm">
            <p class="text-[11px] font-semibold text-zinc-400 uppercase tracking-wider text-center">Akses Cepat Akun Demo (1-Click)</p>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="setDemo('dika@sirkelku.id', 'password123')" class="p-2 rounded-lg bg-zinc-50 hover:bg-zinc-100 text-center text-xs font-medium border border-zinc-200 text-zinc-800 transition-colors truncate">
                    Dika (Gamer)
                </button>
                <button type="button" onclick="setDemo('clara@sirkelku.id', 'password123')" class="p-2 rounded-lg bg-zinc-50 hover:bg-zinc-100 text-center text-xs font-medium border border-zinc-200 text-zinc-800 transition-colors truncate">
                    Clara (Musik)
                </button>
                <button type="button" onclick="setDemo('raihan@sirkelku.id', 'password123')" class="p-2 rounded-lg bg-zinc-50 hover:bg-zinc-100 text-center text-xs font-medium border border-zinc-200 text-zinc-800 transition-colors truncate">
                    Raihan (Dev)
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
