{!! view_render_event('bagisto.shop.layout.header.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    use App\Helpers\BeyondaryTheme;

    $channel = core()->getCurrentChannel();
    $locale = app()->getLocale();

    $navigation = $themeCustomizationRepository
        ->findWhere([
            'type' => 'static_content',
            'status' => 1,
            'theme_code' => $channel->theme,
            'channel_id' => $channel->id,
        ])
        ->first(fn ($item) => $item->name === BeyondaryTheme::NAVIGATION_NAME);

    $navOptions = $navigation?->translate($locale)?->options;
    $defaultNav = BeyondaryTheme::defaultNavigationFields($locale);

    $announcement = $navOptions['announcement'] ?? $defaultNav['announcement'];
    $navLinks = collect($navOptions['links'] ?? $defaultNav['links'])
        ->sortBy('sort_order')
        ->values()
        ->all();
@endphp

<div class="bg-brand-earth text-white text-xs md:text-sm text-center py-2 px-4 flex justify-center items-center gap-2 tracking-wide">
    <i class="fa-solid fa-plane" aria-hidden="true"></i>
    {{ $announcement }}
</div>

<header class="bg-brand-light shadow-sm sticky top-0 z-50 border-b border-[#EAE5DA]">
    <div class="container mx-auto px-4 lg:px-8 py-3 md:py-4 flex justify-between items-center">
        <button
            type="button"
            class="lg:hidden text-brand-dark hover:text-brand-gold text-2xl transition"
            aria-label="Menu"
            onclick="document.getElementById('beyondary-mobile-menu').classList.toggle('hidden')"
        >
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>

        <x-shop::layouts.partials.logo
            id="fallback-logo-header"
            image-class="h-10 md:h-14"
        />

        <nav class="hidden lg:flex space-x-8 text-sm font-medium uppercase tracking-wider text-brand-dark/80">
            @foreach ($navLinks as $link)
                <a href="{{ $link['url'] }}" class="hover:text-brand-gold transition">
                    {{ $link['title'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center space-x-4 md:space-x-6">
            <a
                href="{{ route('shop.search.index') }}"
                class="text-brand-dark/80 hover:text-brand-gold transition hidden md:block"
                aria-label="@lang('shop::app.components.layouts.header.desktop.bottom.search-text')"
            >
                <i class="fa-solid fa-magnifying-glass text-lg" aria-hidden="true"></i>
            </a>

            @if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1)
                @include('shop::components.layouts.header.locale-currency-dropdown')
            @endif

            <div class="text-brand-dark/80 hover:text-brand-gold transition relative flex items-center">
                @include('shop::checkout.cart.mini-cart')
            </div>
        </div>
    </div>

    <div
        id="beyondary-mobile-menu"
        class="hidden lg:hidden bg-brand-light border-t border-[#EAE5DA] px-4 py-4 space-y-4 shadow-inner"
    >
        @foreach ($navLinks as $link)
            <a href="{{ $link['url'] }}" class="block text-brand-dark font-medium">
                {{ $link['title'] }}
            </a>
        @endforeach

        @if(core()->getCurrentChannel()->locales()->count() > 1 || core()->getCurrentChannel()->currencies()->count() > 1)
            <div class="border-t border-[#EAE5DA] pt-4 flex items-center justify-between gap-4">
                <span class="text-brand-dark text-sm">@lang('beyondary.footer.locale_currency')</span>
                <select
                    class="bg-white border border-[#EAE5DA] text-brand-dark text-sm p-1 rounded outline-none"
                    onchange="if (this.value) window.location.href = this.value"
                >
                    <optgroup label="Locale">
                        @foreach (core()->getCurrentChannel()->locales()->orderBy('name')->get() as $locale)
                            <option
                                value="{{ request()->fullUrlWithQuery(['locale' => $locale->code]) }}"
                                @selected(app()->getLocale() === $locale->code)
                            >
                                {{ $locale->name }}
                            </option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Currency">
                        @foreach (core()->getCurrentChannel()->currencies as $currency)
                            <option
                                value="{{ request()->fullUrlWithQuery(['currency' => $currency->code]) }}"
                                @selected(core()->getCurrentCurrencyCode() === $currency->code)
                            >
                                {{ $currency->symbol }} {{ $currency->code }}
                            </option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
        @endif
    </div>
</header>

{!! view_render_event('bagisto.shop.layout.header.after') !!}
