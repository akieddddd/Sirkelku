@props([
    'iconClass' => 'w-9 h-9',
    'textSize' => 'text-xl',
    'showBadge' => false,
    'badge' => 'Pelajar',
    'iconOnly' => false,
    'responsive' => false,
    'dark' => '#1E293B',
    'accent' => '#FF4500',
    'textColor' => 'text-slate-900',
    'href' => null,
])

@php
    $tag = $href ? 'a' : 'div';
    $isIconOnly = filter_var($iconOnly, FILTER_VALIDATE_BOOLEAN) || $iconOnly === true;
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => 'inline-flex flex-row items-center ' . ($isIconOnly ? 'justify-center' : 'gap-2.5') . ' group select-none flex-nowrap shrink-0' . ($href ? ' cursor-pointer' : '')]) }} style="display: inline-flex !important; flex-direction: row !important; align-items: center !important; {{ $isIconOnly ? 'justify-content: center !important;' : 'gap: 10px !important;' }} flex-wrap: nowrap !important; text-decoration: none !important; vertical-align: middle !important;">
    <x-logo-icon :class="$iconClass . ' shrink-0 transition-transform duration-200 group-hover:scale-105'" :dark="$dark" :accent="$accent" />
    
    @if(!$isIconOnly)
        <div class="{{ $responsive ? 'hidden xl:inline-flex' : 'inline-flex' }} flex-row items-center gap-1.5 flex-nowrap shrink-0" style="display: inline-flex; flex-direction: row; align-items: center; gap: 6px; flex-shrink: 0; white-space: nowrap;">
            <span class="{{ $textSize }} font-black tracking-tight {{ $textColor }} transition-colors whitespace-nowrap" style="white-space: nowrap !important; font-weight: 900 !important; line-height: 1 !important; display: inline-block !important;">
                sirkelku<span style="color: {{ $accent }};">.</span>
            </span>
            @if($showBadge)
                <span class="text-[10px] font-extrabold text-[#2D472C] bg-[#FFF0E6] border border-[#CDE0CD] px-2 py-0.5 rounded-full whitespace-nowrap" style="white-space: nowrap !important; display: inline-block !important;">
                    {{ $badge }}
                </span>
            @endif
        </div>
    @endif
</{{ $tag }}>
