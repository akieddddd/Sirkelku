<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8FAFC]">
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
<body class="min-h-full flex items-center justify-center p-4 bg-[#F8FAFC] text-slate-800 antialiased selection:bg-[#588157] selection:text-white">

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
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold">
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
                    <div class="sk-password-wrap">
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                            class="sk-input">
                        <button type="button" id="togglePasswordBtn" onclick="togglePasswordVisibility()" 
                            class="sk-password-toggle" 
                            title="Tampilkan kata sandi" aria-label="Tampilkan kata sandi">
                            <svg id="eyeOpenIcon" class="w-4 h-4" style="display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosedIcon" class="w-4 h-4" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-[#588157] bg-white border-slate-300 focus:ring-[#588157]">
                        <span class="text-xs text-slate-600 font-medium">Ingat Saya</span>
                    </label>
                </div>

                <button type="submit" class="sk-btn-primary w-full text-sm py-3">
                    Masuk Sekarang
                </button>
            </form>

            <div class="pt-4 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Belum punya akun pelajar? 
                    <a href="{{ route('register') }}" class="font-bold text-[#588157] hover:text-[#476A46] hover:underline ml-1">Daftar Sekarang</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpenIcon');
            const eyeClosed = document.getElementById('eyeClosedIcon');
            const toggleBtn = document.getElementById('togglePasswordBtn');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
                toggleBtn.setAttribute('title', 'Sembunyikan kata sandi');
                toggleBtn.setAttribute('aria-label', 'Sembunyikan kata sandi');
            } else {
                passwordInput.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
                toggleBtn.setAttribute('title', 'Tampilkan kata sandi');
                toggleBtn.setAttribute('aria-label', 'Tampilkan kata sandi');
            }
        }

        function setDemo(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>
