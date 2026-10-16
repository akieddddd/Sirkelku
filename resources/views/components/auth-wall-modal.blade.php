<!-- Auth Wall Modal Component (Modern, Bespoke Student Portal) -->
<div x-data="{ 
        open: false, 
        message: 'Masuk ke Sirkelku dulu yuk buat like, komentar, atau simpan postingan ini!' 
     }"
     @open-auth-modal.window="open = true; if($event.detail && $event.detail.message) message = $event.detail.message"
     @keydown.escape.window="open = false"
     x-cloak>
    
    <!-- Backdrop & Modal Container -->
    <div x-show="open" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-0"
         aria-labelledby="auth-modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop with Blur -->
        <div x-show="open"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-950/70 dark:bg-black/85 backdrop-blur-sm transition-opacity"
             @click="open = false"></div>

        <!-- Modal Dialog -->
        <div x-show="open"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 p-6 sm:p-8 shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md text-left z-10 space-y-5">
            
            <!-- Close Button -->
            <button type="button" 
                    @click="open = false"
                    class="absolute top-4 right-4 p-2 rounded-full text-slate-400 hover:text-slate-700 dark:hover:text-zinc-200 hover:bg-slate-100 dark:hover:bg-zinc-800 transition-colors cursor-pointer"
                    aria-label="Tutup modal">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Header with Active Badge & Bottts Avatars -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider text-[#FF4500] bg-[#FFF0E6] dark:bg-[#FF4500]/15 border border-[#F0D5C6] dark:border-[#FF4500]/30">
                        <span class="w-2 h-2 rounded-full bg-[#FF4500] animate-pulse"></span>
                        Tongkrongan Pelajar
                    </span>
                    <!-- 3 Stacked Robot Avatars -->
                    <div class="flex -space-x-2 overflow-hidden pr-8">
                        <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-orange-100" src="https://api.dicebear.com/7.x/bottts/svg?seed=dika_pratama" alt="Siswa">
                        <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-emerald-100" src="https://api.dicebear.com/7.x/bottts/svg?seed=clara_indie" alt="Siswa">
                        <img class="inline-block h-7 w-7 rounded-full ring-2 ring-white dark:ring-zinc-900 bg-sky-100" src="https://api.dicebear.com/7.x/bottts/svg?seed=keiko_art" alt="Siswa">
                    </div>
                </div>

                <div class="space-y-1">
                    <h3 class="text-xl font-black text-slate-900 dark:text-zinc-100 tracking-tight" id="auth-modal-title">
                        Mau Ikut Nimbrung?
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-zinc-300 font-medium leading-relaxed" x-text="message">
                        Masuk ke Sirkelku dulu yuk buat berinteraksi, menyukai, dan membuat postingan!
                    </p>
                </div>
            </div>

            <!-- Authentic Student Hobby Tags -->
            <div class="p-4 rounded-2xl bg-slate-100/80 dark:bg-zinc-950/70 border border-slate-200/90 dark:border-zinc-800/90 space-y-2.5">
                <div class="flex items-center gap-1.5 text-[10.5px] font-extrabold text-slate-500 dark:text-zinc-400 uppercase tracking-wider">
                    <svg class="w-3.5 h-3.5 text-[#FF4500] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.527.82-1.17 2.14-1.393 3.633a6.837 6.837 0 00-1.782-.977 1 1 0 00-1.25.437A9.972 9.972 0 005 10c0 4.418 3.582 8 8 8a7.994 7.994 0 005.657-2.343A7.992 7.992 0 0020 10a9.97 9.97 0 00-.773-3.856 1 1 0 00-1.25-.437 6.83 6.83 0 00-1.782.977c-.223-1.493-.866-2.813-1.393-3.633-.208-.322-.477-.65-.822-.88a1 1 0 00-.585-.118zM12 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <span>Sirkel ramai yang bisa kamu ikuti:</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-0.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-zinc-900 text-slate-800 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-[#FF4500]/60 dark:hover:border-[#FF4500]/60 hover:text-[#FF4500] dark:hover:text-[#FF4500] hover:scale-105 transition-all duration-150 cursor-default select-none">
                        <span class="text-[#FF4500] font-black mr-0.5">#</span>Valorant
                    </span>
                    <span class="inline-flex items-center gap-0.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-zinc-900 text-slate-800 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-[#FF4500]/60 dark:hover:border-[#FF4500]/60 hover:text-[#FF4500] dark:hover:text-[#FF4500] hover:scale-105 transition-all duration-150 cursor-default select-none">
                        <span class="text-[#FF4500] font-black mr-0.5">#</span>GitarAkustik
                    </span>
                    <span class="inline-flex items-center gap-0.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-zinc-900 text-slate-800 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-[#FF4500]/60 dark:hover:border-[#FF4500]/60 hover:text-[#FF4500] dark:hover:text-[#FF4500] hover:scale-105 transition-all duration-150 cursor-default select-none">
                        <span class="text-[#FF4500] font-black mr-0.5">#</span>WebDev
                    </span>
                    <span class="inline-flex items-center gap-0.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-zinc-900 text-slate-800 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-[#FF4500]/60 dark:hover:border-[#FF4500]/60 hover:text-[#FF4500] dark:hover:text-[#FF4500] hover:scale-105 transition-all duration-150 cursor-default select-none">
                        <span class="text-[#FF4500] font-black mr-0.5">#</span>DigitalArt
                    </span>
                    <span class="inline-flex items-center gap-0.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white dark:bg-zinc-900 text-slate-800 dark:text-zinc-100 border border-slate-200 dark:border-zinc-700/80 shadow-xs hover:border-[#FF4500]/60 dark:hover:border-[#FF4500]/60 hover:text-[#FF4500] dark:hover:text-[#FF4500] hover:scale-105 transition-all duration-150 cursor-default select-none">
                        <span class="text-[#FF4500] font-black mr-0.5">#</span>Futsal
                    </span>
                </div>
            </div>

            <!-- Action Buttons: Masuk & Daftar -->
            <div class="space-y-2 pt-1">
                <a href="{{ route('register') }}" 
                   class="w-full flex items-center justify-center gap-2 py-3 px-5 rounded-full bg-[#FF4500] hover:bg-[#E03E00] text-white font-bold text-xs shadow-md hover:shadow-lg transition-all text-center transform hover:-translate-y-0.5">
                    <span>Daftar Sekarang (Gratis)</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
                <a href="{{ route('login') }}" 
                   class="w-full flex items-center justify-center py-2.5 px-4 rounded-full border border-slate-200 dark:border-zinc-700 text-slate-700 dark:text-zinc-200 hover:bg-slate-50 dark:hover:bg-zinc-800 font-bold text-xs transition-colors text-center">
                    Sudah Punya Akun? Masuk
                </a>
            </div>

            <!-- Footer Note -->
            <p class="text-[11px] text-center text-slate-400 dark:text-zinc-500 font-medium pt-1">
                Khusus dibuat untuk pelajar SMA, SMK, dan sederajat se-Indonesia.
            </p>
        </div>
    </div>
</div>
