@php
    use Webkul\Theme\Models\ThemeCustomization;

    $channel = core()->getCurrentChannel();

    $homeCustomizations = collect($customizations)->filter(
        fn ($customization) => $customization->type !== ThemeCustomization::FOOTER_LINKS
    );

    $useAdminSections = $homeCustomizations->isNotEmpty();
@endphp

@push('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}" />
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}" />
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}" />
@endPush

@push('scripts')
    @if (! empty($categories))
        <script>
            localStorage.setItem('categories', JSON.stringify(@json($categories)));
        </script>
    @endif
@endpush

<x-shop::layouts>
    <x-slot:title>
        {{ $channel->home_seo['meta_title'] ?? $channel->name }}
    </x-slot>

    @if ($useAdminSections)
        @foreach ($homeCustomizations as $customization)
            @php ($data = $customization->options) @endphp

            @switch ($customization->type)
                @case (ThemeCustomization::IMAGE_CAROUSEL)
                    <x-shop::carousel.hero :options="$data" />
                    @break

                @case (ThemeCustomization::SERVICES_CONTENT)
                    @include('shop::home.partials.trust-badges', [
                        'services' => $data['services'] ?? null,
                    ])
                    @break

                @case (ThemeCustomization::CATEGORY_CAROUSEL)
                    @include('shop::home.partials.categories', [
                        'title' => $data['title'] ?? null,
                    ])
                    @break

                @case (ThemeCustomization::PRODUCT_CAROUSEL)
                    @include('shop::home.partials.product-carousel-section', [
                        'title' => $data['title'] ?? null,
                        'filters' => $data['filters'] ?? [],
                    ])
                    @break

                @case (ThemeCustomization::STATIC_CONTENT)
                    @include('shop::home.partials.static-content', ['data' => $data])
                    @break
            @endswitch
        @endforeach

        @include('shop::home.partials.newsletter')
    @else
        {{-- Fallback: mockup layout when no Admin → Settings → Themes entries for this channel/theme --}}
        @include('shop::home.partials.hero')
        @include('shop::home.partials.trust-badges')
        @include('shop::home.partials.categories')
        @include('shop::home.partials.featured-products')
        @include('shop::home.partials.our-story')
        @include('shop::home.partials.newsletter')
    @endif
</x-shop::layouts>
