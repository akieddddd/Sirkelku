<!DOCTYPE html>
<html lang="id" class="h-full bg-[#F8FAFC]">
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
<body class="min-h-full flex items-center justify-center p-4 bg-[#F8FAFC] text-slate-800 antialiased selection:bg-[#588157] selection:text-white">

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
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-slate-400 text-xs font-bold pointer-events-none">@</span>
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
                    <div class="sk-password-wrap">
                        <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                            class="sk-input">
                        <button type="button" onclick="togglePassword('password', 'eyeOpen_pw', 'eyeClosed_pw', this)" 
                            class="sk-password-toggle" 
                            title="Tampilkan kata sandi" aria-label="Tampilkan kata sandi">
                            <svg id="eyeOpen_pw" class="w-4 h-4" style="display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosed_pw" class="w-4 h-4" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="sk-password-wrap">
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi kata sandi"
                            class="sk-input">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eyeOpen_cf', 'eyeClosed_cf', this)" 
                            class="sk-password-toggle" 
                            title="Tampilkan kata sandi" aria-label="Tampilkan kata sandi">
                            <svg id="eyeOpen_cf" class="w-4 h-4" style="display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosed_cf" class="w-4 h-4" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="sk-btn-primary w-full text-sm py-3">
                        Lanjut ke Onboarding &rarr;
                    </button>
                </div>
            </form>

            <div class="pt-4 border-t border-slate-200 text-center">
                <p class="text-xs text-slate-500 font-medium">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-[#588157] hover:text-[#476A46] hover:underline ml-1">Masuk Saja</a>
                </p>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId, openIconId, closedIconId, btn) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(openIconId);
            const eyeClosed = document.getElementById(closedIconId);

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
                btn.setAttribute('title', 'Sembunyikan kata sandi');
                btn.setAttribute('aria-label', 'Sembunyikan kata sandi');
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
                btn.setAttribute('title', 'Tampilkan kata sandi');
                btn.setAttribute('aria-label', 'Tampilkan kata sandi');
            }
        }
    </script>
</body>
</html>
