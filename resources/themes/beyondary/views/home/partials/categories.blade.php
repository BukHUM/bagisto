@inject('categoryRepository', 'Webkul\Category\Repositories\CategoryRepository')

@php
    $featuredCategories = collect($categoryRepository->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id))
        ->take(4);

    $fallbackImages = [
        'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?q=80&w=1000&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1591561954557-26941169b49e?q=80&w=1000&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=1000&auto=format&fit=crop',
        'https://images.unsplash.com/photo-1544531585-9847b68c8c86?q=80&w=1000&auto=format&fit=crop',
    ];
@endphp

<section id="categories" class="py-16 md:py-24 container mx-auto px-4">
    <div class="text-center mb-12">
        <h2 class="font-serif text-3xl md:text-4xl text-brand-dark font-bold mb-3">
            @lang('beyondary.categories.title')
        </h2>
        <div class="w-16 h-1 bg-brand-gold mx-auto"></div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @forelse ($featuredCategories as $index => $category)
            @php
                $imageUrl = $category->banner_url ?: ($category->logo_url ?: ($fallbackImages[$index] ?? $fallbackImages[0]));
            @endphp
            <a
                href="{{ $category->url }}"
                class="group relative h-64 md:h-80 overflow-hidden block rounded-sm"
            >
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $category->name }}"
                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/30 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-6">
                    <h3 class="text-white font-serif text-xl md:text-2xl mb-1">{{ $category->name }}</h3>
                    <span class="text-brand-gold text-sm group-hover:underline underline-offset-4">
                        @lang('beyondary.nav.shop')
                        <i class="fa-solid fa-arrow-right ml-1 text-xs" aria-hidden="true"></i>
                    </span>
                </div>
            </a>
        @empty
            @foreach ($fallbackImages as $index => $imageUrl)
                <a href="{{ route('shop.search.index') }}" class="group relative h-64 md:h-80 overflow-hidden block rounded-sm">
                    <img src="{{ $imageUrl }}" alt="" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-brand-dark/80 via-brand-dark/30 to-transparent"></div>
                </a>
            @endforeach
        @endforelse
    </div>
</section>
