@php
    $locales = core()->getCurrentChannel()->locales()->orderBy('name')->get();
    $currencies = core()->getCurrentChannel()->currencies;
@endphp

<div class="relative group hidden sm:block">
    <button
        type="button"
        class="flex items-center space-x-1 text-sm text-brand-dark/80 hover:text-brand-gold transition"
    >
        <i class="fa-solid fa-globe" aria-hidden="true"></i>
        <span>{{ strtoupper(app()->getLocale()) }} / {{ core()->getCurrentCurrencyCode() }}</span>
        <i class="fa-solid fa-chevron-down text-xs" aria-hidden="true"></i>
    </button>

    <div class="absolute right-0 mt-2 w-40 bg-white border border-[#EAE5DA] shadow-lg rounded opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-300 z-50">
        @foreach ($locales as $locale)
            @php
                $localeUrl = request()->fullUrlWithQuery(['locale' => $locale->code]);
            @endphp
            <a
                href="{{ $localeUrl }}"
                class="block px-4 py-2 text-sm text-brand-dark hover:bg-brand-light {{ app()->getLocale() === $locale->code ? 'bg-brand-light/50' : '' }}"
            >
                {{ $locale->name }}
            </a>
        @endforeach

        @foreach ($currencies as $currency)
            @php
                $currencyUrl = request()->fullUrlWithQuery(['currency' => $currency->code]);
            @endphp
            <a
                href="{{ $currencyUrl }}"
                class="block px-4 py-2 text-sm text-brand-dark hover:bg-brand-light {{ core()->getCurrentCurrencyCode() === $currency->code ? 'bg-brand-light/50' : '' }}"
            >
                {{ $currency->symbol }} {{ $currency->code }}
            </a>
        @endforeach
    </div>
</div>
