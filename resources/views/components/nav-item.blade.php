@props([
    'href' => '#',
    'active' => false,
    'label' => '',
    'badge' => 0,
    'badgeClass' => null,
])

<a href="{{ $href }}" 
   title="{{ $label }}"
   {{ $attributes->merge(['class' => 'group flex items-center justify-center xl:justify-start xl:gap-4 p-3 xl:px-4 xl:py-3 rounded-full transition-all duration-150 ' . ($active ? 'bg-[#FFF0E6] text-[#FF4500] font-bold' : 'text-slate-700 hover:bg-slate-100 hover:text-slate-900 font-semibold')]) }}>
    
    <!-- Icon Container -->
    <div class="relative shrink-0 flex items-center justify-center">
        {{ $slot }}

        <!-- Badge for collapsed icon-only mode (<xl) only when badgeClass is explicitly set -->
        @if(!empty($badgeClass))
            <span class="{{ $badgeClass }} xl:hidden absolute -top-1.5 -right-1.5 min-w-4 h-4 px-1 bg-[#FF4500] text-white text-[10px] font-extrabold rounded-full flex items-center justify-center ring-2 ring-white shadow-xs"
                style="{{ (int)($badge ?? 0) > 0 ? '' : 'display: none !important;' }}">
                {{ (int)($badge ?? 0) > 9 ? '9+' : $badge }}
            </span>
        @endif
    </div>

    <!-- Label (visible on >=xl) -->
    <span class="hidden xl:inline text-base leading-none tracking-tight truncate flex-1">
        {{ $label }}
    </span>

    <!-- Badge for expanded mode (>=xl) only when badgeClass is explicitly set -->
    @if(!empty($badgeClass))
        <span class="{{ $badgeClass }} hidden xl:inline-flex px-2 py-0.5 rounded-full text-[11px] font-extrabold bg-[#FF4500] text-white shrink-0"
            style="{{ (int)($badge ?? 0) > 0 ? '' : 'display: none !important;' }}">
            {{ (int)($badge ?? 0) > 9 ? '9+' : $badge }}
        </span>
    @endif
</a>
