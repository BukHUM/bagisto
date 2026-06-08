@props([
    'id' => 'fallback-logo',
    'imageClass' => 'h-10 md:h-14',
])

<a
    href="{{ route('shop.home.index') }}"
    {{ $attributes->class('flex items-center justify-center') }}
>
    <img
        src="{{ asset('themes/shop/beyondary/images/logo.png') }}"
        alt="{{ core()->getCurrentChannel()->name ?? 'Beyondary' }}"
        class="{{ $imageClass }} w-auto object-contain blend-multiply"
        onerror="this.onerror=null; this.style.display='none'; document.getElementById('{{ $id }}').style.display='flex';"
    >

    <div
        id="{{ $id }}"
        class="hidden flex-col items-center"
    >
        <span class="font-serif text-2xl md:text-[1.75rem] font-bold text-brand-dark lowercase tracking-wider leading-none">
            @lang('beyondary.logo.fallback')
        </span>
        <div class="flex items-center w-full mt-1 gap-2">
            <div class="h-[2px] bg-brand-dark flex-grow"></div>
            <span class="text-[0.6rem] md:text-[0.65rem] font-bold text-brand-dark tracking-widest uppercase">
                @lang('beyondary.logo.tagline')
            </span>
        </div>
    </div>
</a>
