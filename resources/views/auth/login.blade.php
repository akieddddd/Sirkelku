<!DOCTYPE html>
<html lang="id" class="h-full bg-[#FAF8F5]">
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
</head>
<body class="min-h-full flex items-center justify-center p-4 bg-[#FAF8F5] text-slate-800 antialiased selection:bg-orange-500 selection:text-white">

    <div class="max-w-md w-full space-y-6 my-8">
        
        <!-- Brand Header -->
        <div class="text-center space-y-2">
            <a href="{{ url('/') }}" class="inline-block group">
                <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo" class="w-14 h-14 object-contain mx-auto transition-transform group-hover:scale-105 duration-200">
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Masuk ke Sirkelku</h1>
            <p class="text-xs text-slate-500 font-medium">Temukan teman mabar, sirkel hobi, dan tongkrongan pelajar</p>
        </div>

        <!-- Login Card -->
        <div class="sk-card p-6 sm:p-7 space-y-5">
            
            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('info'))
                <div class="p-3.5 rounded-xl bg-orange-50 border border-orange-200 text-orange-900 text-xs font-semibold">
                    {{ session('info') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Email atau Username</label>
                    <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@sekolah.id atau username"
                        class="sk-input">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700">Kata Sandi</label>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="••••••••"
                        class="sk-input">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-orange-500 bg-white border-slate-300 focus:ring-orange-500">
                        <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="sk-btn-primary w-full text-sm py-3">
                    Masuk Sekarang
                </button>
            </form>

            <div class="pt-4 border-t border-orange-100 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Belum punya akun pelajar? 
                    <a href="{{ route('register') }}" class="font-bold text-orange-600 hover:text-orange-700 hover:underline ml-1">Daftar Sekarang</a>
                </p>
            </div>
        </div>

        <!-- Quick Demo Accounts -->
        <div class="sk-card p-4 text-slate-700 space-y-3">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Akses Cepat Akun Demo (1-Click)</p>
            <div class="grid grid-cols-3 gap-2">
                <button type="button" onclick="setDemo('dika@sirkelku.id', 'password123')" class="p-2.5 rounded-xl bg-orange-50/80 hover:bg-orange-100 text-center text-xs font-bold border border-orange-200/80 text-orange-800 transition-all truncate">
                    Dika (Gamer)
                </button>
                <button type="button" onclick="setDemo('clara@sirkelku.id', 'password123')" class="p-2.5 rounded-xl bg-orange-50/80 hover:bg-orange-100 text-center text-xs font-bold border border-orange-200/80 text-orange-800 transition-all truncate">
                    Clara (Musik)
                </button>
                <button type="button" onclick="setDemo('raihan@sirkelku.id', 'password123')" class="p-2.5 rounded-xl bg-orange-50/80 hover:bg-orange-100 text-center text-xs font-bold border border-orange-200/80 text-orange-800 transition-all truncate">
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
