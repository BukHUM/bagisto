@php
    $channel = core()->getCurrentChannel();
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

    @include('shop::home.partials.hero')
    @include('shop::home.partials.trust-badges')
    @include('shop::home.partials.categories')
    @include('shop::home.partials.featured-products')
    @include('shop::home.partials.our-story')
    @include('shop::home.partials.newsletter')
</x-shop::layouts>
