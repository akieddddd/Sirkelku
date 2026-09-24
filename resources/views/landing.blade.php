<!DOCTYPE html>
<html lang="id" class="scroll-smooth h-full bg-[#F8FAFC] text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sirkelku - Tempat Nongkrong Digital Kamu</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(128,128,128,0.1) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(128,128,128,0.1) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .glow-effect {
            box-shadow: 0 0 40px -10px rgba(88, 129, 87, 0.4);
        }
        
        /* Custom Button utility for landing since it was missing */
        .btn-fitur {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 9999px;
            font-weight: 600;
            transition: all 0.2s ease;
            background-color: white;
            color: #1E293B;
            border: 1px solid #E2E8F0;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .btn-fitur:hover {
            border-color: #CBD5E1;
            background-color: #F8FAFC;
        }
        
        .btn-slider-nav {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3rem;
            height: 3rem;
            border-radius: 9999px;
            background-color: white;
            border: 1px solid #E2E8F0;
            color: #1E293B;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }
        .btn-slider-nav:hover {
            background-color: #F8FAFC;
            border-color: #CBD5E1;
            transform: scale(1.05);
        }
        .button-elem {
            width: 1rem;
            height: 1rem;
            fill: currentColor;
        }
        .btn-slider-prev .button-elem {
            transform: scaleX(-1);
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 antialiased selection:bg-[#EAF0EA] selection:text-[#2D472C] overflow-x-hidden transition-colors duration-300">
    
    <!-- Background Elements -->
    <div class="fixed inset-0 z-[-1] bg-grid-pattern opacity-50"></div>
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-slate-400/20 rounded-full blur-[120px] pointer-events-none z-[-1]"></div>
    
    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 transition-all duration-300 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="inline-block">
                        <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo" class="h-10 w-auto drop-shadow-sm">
                    </a>
                    <a href="{{ url('/') }}">
                        <span class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-[#588157]">
                            Sirkelku.
                        </span>
                    </a>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    @auth
                        <a href="{{ route('feed.index') }}" class="px-5 py-2.5 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-900 font-semibold transition-all duration-300 border border-slate-200 backdrop-blur-sm">
                            Masuk ke Sirkel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden md:inline-flex px-5 py-2.5 text-slate-600 hover:text-slate-900 font-medium transition-colors">
                            Log In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-[#588157] hover:bg-[#476A46] text-white font-semibold transition-all duration-300 glow-effect hover:scale-105 active:scale-95 shadow-md">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
            
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#EAF0EA] border border-[#CDE0CD] text-[#2D472C] text-sm font-medium mb-8 shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#588157] opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-[#588157]"></span>
                </span>
                Tempat Nongkrong Digital No. 1
            </div>

            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight">
                Cari Teman Se-Frekuensi, <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#588157] via-slate-700 to-[#588157]">
                    Bikin Sirkel Sendiri!
                </span>
            </h1>
            
            <p class="mt-4 max-w-2xl mx-auto text-xl text-slate-600 mb-10 leading-relaxed">
                Platform komunitas seru buat kamu yang ingin berbagi hobi, diskusi, atau sekadar mabar. Temukan sirkel asikmu di sini sekarang juga.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                @auth
                    <a href="{{ route('feed.index') }}" class="btn-fitur hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Mulai Nongkrong</span>
                    </a>
                @else
                    <a href="#fitur" class="btn-fitur hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Fitur Andalan</span>
                    </a>
                @endauth
            </div>
            
        </div>
    </main>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-slate-50 border-t border-slate-200 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-slate-900">Fitur Andalan Sirkelku</h2>
                <p class="text-slate-600 text-lg">Semua yang kamu butuhkan buat nongkrong digital.</p>
            </div>
            
            <!-- Slider: arrows on left & right sides of card -->
            <div x-data="{ activeSlide: 0, slides: 3 }" class="relative max-w-3xl mx-auto flex items-center gap-4">
                
                <!-- Arrow Left -->
                <button @click="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" class="btn-slider-nav btn-slider-prev flex-shrink-0" aria-label="Previous">
                    <div class="button-box">
                        <svg class="button-elem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 40">
                            <path d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3 .8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"></path>
                        </svg>
                    </div>
                </button>

                <!-- Card Area -->
                <div class="flex-1 overflow-hidden relative min-h-[280px]">
                    <!-- Feature 1 -->
                    <div x-show="activeSlide === 0" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full" class="absolute w-full p-8 rounded-3xl bg-white border border-slate-200 shadow-sm">
                        <div class="w-14 h-14 rounded-2xl bg-[#EAF0EA] flex items-center justify-center mb-6">
                            <svg class="w-7 h-7 text-[#588157]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Satu Sirkel</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Cari dan gabung komunitas yang sesuai dengan hobimu. Atau buat sirkelmu sendiri dan jadilah ketua!</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div x-show="activeSlide === 1" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full" class="absolute w-full p-8 rounded-3xl bg-white border border-slate-200 shadow-sm">
                        <div class="w-14 h-14 rounded-2xl bg-[#EAF0EA] flex items-center justify-center mb-6">
                            <svg class="w-7 h-7 text-[#588157]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Tongkrongan.id</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Forum diskusi tanpa batas. Tanyakan sesuatu, bagikan cerita, dan bahas topik hangat bareng anak sirkel lain.</p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div x-show="activeSlide === 2" x-cloak x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full" class="absolute w-full p-8 rounded-3xl bg-white border border-slate-200 shadow-sm">
                        <div class="w-14 h-14 rounded-2xl bg-[#EAF0EA] flex items-center justify-center mb-6">
                            <svg class="w-7 h-7 text-[#588157]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">Teman Main</h3>
                        <p class="text-slate-600 text-lg leading-relaxed">Pengen mabar game atau nongki real life tapi nggak ada temen? Request teman main langsung di sini!</p>
                    </div>
                </div>

                <!-- Arrow Right -->
                <button @click="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1" class="btn-slider-nav flex-shrink-0" aria-label="Next">
                    <div class="button-box">
                        <svg class="button-elem" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 40">
                            <path d="M46 20.038c0-.7-.3-1.5-.8-2.1l-16-17c-1.1-1-3.2-1.4-4.4-.3-1.2 1.1-1.2 3.3 0 4.4l11.3 11.9H3c-1.7 0-3 1.3-3 3s1.3 3 3 3h33.1l-11.3 11.9c-1 1-1.2 3.3 0 4.4 1.2 1.1 3.3 .8 4.4-.3l16-17c.5-.5.8-1.1.8-1.9z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white py-12 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Sirkelku Logo" class="h-8 w-auto grayscale opacity-50">
                <span class="text-xl font-bold text-slate-400">Sirkelku.</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">
                &copy; {{ date('Y') }} Sirkelku. Hak Cipta Dilindungi.
            </p>
            <div class="flex gap-4">
                <a href="#" class="text-slate-400 hover:text-slate-900 transition-colors">
                    <span class="sr-only">Twitter</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                    </svg>
                </a>
                <a href="#" class="text-slate-400 hover:text-slate-900 transition-colors">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </footer>
</body>
</html>
