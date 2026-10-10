@props([
    'hobbies' => collect(),
    'userCommunities' => collect(),
])

<div class="border-b border-slate-200 p-4 bg-white transition-colors" 
     x-data="{ 
         content: '{{ old('content', '') }}', 
         hasImage: false, 
         imagePreview: null,
         visibilityOpen: false,
         hobbyOpen: false,
         selectedCommunityId: '{{ old('community_id', '') }}',
         selectedCommunityName: 'Publik (Semua Pelajar)',
         selectedHobbyId: '{{ old('hobby_id', '') }}',
         selectedHobbyName: ''
     }">
    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
        @csrf

        <div class="flex items-start gap-3">
            <!-- User Avatar -->
            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" 
                class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-200 shrink-0 mt-0.5">

            <div class="flex-1 min-w-0">
                <!-- Visibility Chip (Zephyr Style outline pill) -->
                <div class="relative mb-2">
                    <button type="button" 
                        @click="visibilityOpen = !visibilityOpen" 
                        @click.away="visibilityOpen = false"
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-slate-200 hover:border-slate-300 bg-white text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors cursor-pointer shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span x-text="selectedCommunityName" class="truncate max-w-[200px]">Publik (Semua Pelajar)</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <input type="hidden" name="community_id" :value="selectedCommunityId">

                    <!-- Visibility Dropdown -->
                    <div x-show="visibilityOpen" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        class="absolute left-0 mt-1 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-1.5 z-40">
                        <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            Pilih Target Pembaca
                        </div>
                        <button type="button" 
                            @click="selectedCommunityId = ''; selectedCommunityName = 'Publik (Semua Pelajar)'; visibilityOpen = false"
                            class="w-full text-left px-3.5 py-2 text-xs font-semibold hover:bg-slate-50 flex items-center justify-between"
                            :class="selectedCommunityId === '' ? 'text-[#FF4500] font-bold' : 'text-slate-700'">
                            <span>Publik (Semua Pelajar)</span>
                            <svg x-show="selectedCommunityId === ''" class="w-4 h-4 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </button>
                        @foreach($userCommunities as $comm)
                            <button type="button" 
                                @click="selectedCommunityId = '{{ $comm->id }}'; selectedCommunityName = 'Sirkel: {{ addslashes($comm->name) }}'; visibilityOpen = false"
                                class="w-full text-left px-3.5 py-2 text-xs font-semibold hover:bg-slate-50 flex items-center justify-between"
                                :class="selectedCommunityId === '{{ $comm->id }}' ? 'text-[#FF4500] font-bold' : 'text-slate-700'">
                                <span class="truncate">Sirkel: {{ $comm->name }}</span>
                                <svg x-show="selectedCommunityId === '{{ $comm->id }}'" class="w-4 h-4 text-[#FF4500]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Borderless Textarea -->
                <textarea name="content" 
                    x-model="content"
                    rows="3" 
                    placeholder="Bagikan cerita, karya, atau kabar seru sekolah hari ini..." 
                    class="w-full bg-transparent text-sm text-slate-800 placeholder:text-slate-400 border-0 focus:ring-0 focus:outline-none p-0 resize-none leading-relaxed font-normal"></textarea>

                <!-- Image Preview Box -->
                <div x-show="hasImage" x-cloak class="relative mt-2 rounded-2xl overflow-hidden border border-slate-200 max-h-60 bg-slate-50">
                    <img :src="imagePreview" class="w-full h-full object-cover">
                    <button type="button" 
                        @click="hasImage = false; imagePreview = null; document.getElementById('post-image-input').value = ''" 
                        class="absolute top-2 right-2 w-7 h-7 rounded-full bg-slate-900/80 hover:bg-slate-900 text-white flex items-center justify-center transition-colors text-sm font-bold">
                        &times;
                    </button>
                </div>
            </div>
        </div>

        <!-- Hidden File Input for Image -->
        <input type="file" id="post-image-input" name="image" accept="image/*" class="hidden"
            @change="const file = $event.target.files[0]; if (file) { hasImage = true; imagePreview = URL.createObjectURL(file); }">

        <!-- Composer Toolbar (Zephyr Style) -->
        <div class="flex items-center justify-between pt-2 pl-13 border-t border-slate-100">
            <div class="flex items-center gap-1 sm:gap-2">
                <!-- Upload Image Icon -->
                <label for="post-image-input" 
                    class="p-2 rounded-full text-[#FF4500]/70 hover:text-[#FF4500] hover:bg-[#FFF0E6] transition-colors cursor-pointer flex items-center gap-1.5"
                    title="Foto / Karya">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs font-bold hidden sm:inline text-slate-600">Foto</span>
                </label>

                <!-- Hobby Tag Picker (Dropdown) -->
                <div class="relative" x-data="{ open: false }">
                    <button type="button" 
                        @click="open = !open" 
                        @click.away="open = false"
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold border border-slate-200 hover:border-[#FF4500] text-slate-600 hover:text-[#FF4500] transition-colors bg-white">
                        <span class="text-[#FF4500] font-bold">#</span>
                        <span x-text="selectedHobbyName ? selectedHobbyName : 'Tag Hobi (Opsional)'" class="truncate max-w-[130px]">Tag Hobi (Opsional)</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <input type="hidden" name="hobby_id" :value="selectedHobbyId">

                    <!-- Hobby Dropdown List -->
                    <div x-show="open" x-cloak 
                        class="absolute left-0 mt-1 w-52 max-h-56 overflow-y-auto bg-white rounded-2xl shadow-xl border border-slate-200 py-1.5 z-40 no-scrollbar">
                        <button type="button" 
                            @click="selectedHobbyId = ''; selectedHobbyName = ''; open = false" 
                            class="w-full text-left px-3.5 py-1.5 text-xs text-slate-400 hover:bg-slate-50 font-medium">
                            Tanpa Tag Hobi
                        </button>
                        @foreach($hobbies as $h)
                            <button type="button" 
                                @click="selectedHobbyId = '{{ $h->id }}'; selectedHobbyName = '#{{ addslashes($h->name) }}'; open = false" 
                                class="w-full text-left px-3.5 py-1.5 text-xs font-semibold hover:bg-slate-50 transition-colors flex items-center justify-between"
                                :class="selectedHobbyId === '{{ $h->id }}' ? 'text-[#FF4500] font-bold' : 'text-slate-700'">
                                <span>#{{ $h->name }}</span>
                                <span class="text-[10px] text-slate-400 font-normal">{{ $h->category }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Submit Post Button (Pill, disabled when empty) -->
            <button type="submit" 
                :disabled="content.trim().length === 0 && !hasImage"
                :class="(content.trim().length === 0 && !hasImage) 
                    ? 'bg-slate-200 text-slate-400 cursor-not-allowed opacity-60' 
                    : 'bg-[#FF4500] hover:bg-[#E03E00] text-white cursor-pointer shadow-xs'"
                class="px-5 py-2 rounded-full text-xs font-bold transition-all transform active:scale-95">
                Kirim Postingan
            </button>
        </div>
    </form>
</div>
