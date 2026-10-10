@props([
    'class' => 'w-9 h-9',
    'dark' => '#1E293B',
    'accent' => '#FF4500',
])

<svg {{ $attributes->merge(['class' => $class . ' shrink-0 inline-block']) }} width="36" height="36" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Sirkelku" style="flex-shrink: 0; display: inline-block; vertical-align: middle;">
  <circle cx="22" cy="24" r="15" stroke="{{ $dark }}" stroke-width="6"/>
  <circle cx="42" cy="24" r="15" stroke="{{ $dark }}" stroke-width="6"/>
  <circle cx="32" cy="41" r="15" stroke="{{ $accent }}" stroke-width="6"/>
  <circle cx="32" cy="29.7" r="4.5" fill="{{ $accent }}"/>
</svg>
