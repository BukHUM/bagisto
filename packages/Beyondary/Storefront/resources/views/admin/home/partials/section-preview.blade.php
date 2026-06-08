@props(['type' => 'default', 'compact' => false])

@php
    $brand = '#B88B54';
    $dark = '#3A2618';
    $muted = '#E5DFD4';
    $surface = '#F8F6F0';
@endphp

<div @class([
    'sf-section-preview',
    'sf-section-preview--compact' => $compact,
]) aria-hidden="true">
    @switch($type)
        @case('menu')
            <svg viewBox="0 0 200 48" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <rect width="200" height="10" fill="{{ $dark }}" opacity="0.85" />
                <rect y="12" width="200" height="36" fill="{{ $surface }}" stroke="{{ $muted }}" />
                <rect x="12" y="22" width="36" height="8" rx="1" fill="{{ $brand }}" opacity="0.5" />
                <rect x="70" y="24" width="22" height="4" rx="1" fill="{{ $dark }}" opacity="0.2" />
                <rect x="98" y="24" width="22" height="4" rx="1" fill="{{ $dark }}" opacity="0.2" />
                <rect x="126" y="24" width="22" height="4" rx="1" fill="{{ $dark }}" opacity="0.2" />
            </svg>
            @break

        @case('hero')
            <svg viewBox="0 0 200 80" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <rect width="200" height="80" fill="{{ $surface }}" stroke="{{ $muted }}" />
                <rect x="24" y="22" width="72" height="8" rx="1" fill="{{ $brand }}" opacity="0.7" />
                <rect x="24" y="36" width="96" height="5" rx="1" fill="{{ $dark }}" opacity="0.25" />
                <rect x="24" y="46" width="64" height="5" rx="1" fill="{{ $dark }}" opacity="0.15" />
                <rect x="24" y="58" width="40" height="10" rx="1" fill="{{ $brand }}" />
                <rect x="120" y="16" width="64" height="48" rx="2" fill="{{ $muted }}" />
            </svg>
            @break

        @case('trust')
            <svg viewBox="0 0 200 40" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                @foreach ([12, 72, 132] as $x)
                    <rect x="{{ $x }}" y="6" width="56" height="28" rx="2" fill="{{ $surface }}" stroke="{{ $muted }}" />
                    <circle cx="{{ $x + 12 }}" cy="20" r="6" fill="{{ $brand }}" opacity="0.45" />
                    <rect x="{{ $x + 22 }}" y="14" width="28" height="4" rx="1" fill="{{ $dark }}" opacity="0.2" />
                    <rect x="{{ $x + 22 }}" y="22" width="20" height="3" rx="1" fill="{{ $dark }}" opacity="0.12" />
                @endforeach
            </svg>
            @break

        @case('categories')
            <svg viewBox="0 0 200 56" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <rect x="8" y="4" width="48" height="6" rx="1" fill="{{ $dark }}" opacity="0.25" />
                @foreach ([8, 56, 104, 152] as $x)
                    <rect x="{{ $x }}" y="16" width="40" height="36" rx="2" fill="{{ $muted }}" />
                    <rect x="{{ $x + 6 }}" y="44" width="28" height="4" rx="1" fill="{{ $surface }}" />
                @endforeach
            </svg>
            @break

        @case('products')
            <svg viewBox="0 0 200 56" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <rect x="8" y="4" width="56" height="6" rx="1" fill="{{ $dark }}" opacity="0.25" />
                @foreach ([8, 54, 100, 146] as $x)
                    <rect x="{{ $x }}" y="16" width="40" height="32" rx="2" fill="{{ $surface }}" stroke="{{ $muted }}" />
                    <rect x="{{ $x + 4 }}" y="20" width="32" height="18" rx="1" fill="{{ $muted }}" />
                    <rect x="{{ $x + 4 }}" y="42" width="20" height="3" rx="1" fill="{{ $brand }}" opacity="0.6" />
                @endforeach
            </svg>
            @break

        @case('our_story')
            <svg viewBox="0 0 200 56" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <rect x="8" y="8" width="72" height="40" rx="2" fill="{{ $muted }}" />
                <rect x="92" y="12" width="64" height="6" rx="1" fill="{{ $brand }}" opacity="0.65" />
                <rect x="92" y="24" width="96" height="4" rx="1" fill="{{ $dark }}" opacity="0.18" />
                <rect x="92" y="32" width="88" height="4" rx="1" fill="{{ $dark }}" opacity="0.12" />
                <rect x="92" y="40" width="72" height="4" rx="1" fill="{{ $dark }}" opacity="0.12" />
            </svg>
            @break

        @case('footer')
            <svg viewBox="0 0 200 48" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <rect width="200" height="48" fill="{{ $dark }}" opacity="0.9" />
                <rect x="12" y="10" width="40" height="6" rx="1" fill="{{ $brand }}" opacity="0.55" />
                @foreach ([12, 76, 140] as $x)
                    <rect x="{{ $x }}" y="24" width="36" height="3" rx="1" fill="#fff" opacity="0.35" />
                    <rect x="{{ $x }}" y="32" width="28" height="3" rx="1" fill="#fff" opacity="0.2" />
                @endforeach
            </svg>
            @break

        @default
            <svg viewBox="0 0 200 32" class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <rect width="200" height="32" rx="2" fill="{{ $surface }}" stroke="{{ $muted }}" />
                <rect x="12" y="12" width="80" height="8" rx="1" fill="{{ $dark }}" opacity="0.15" />
            </svg>
    @endswitch
</div>
