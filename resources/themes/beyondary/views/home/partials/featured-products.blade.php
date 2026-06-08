<section id="shop" class="bg-white py-16 md:py-24 border-y border-[#EAE5DA]">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-10">
            <div>
                <h2 class="font-serif text-3xl md:text-4xl text-brand-dark font-bold mb-3">
                    @lang('beyondary.home.featured_title')
                </h2>
                <div class="w-16 h-1 bg-brand-gold"></div>
            </div>
            <a
                href="{{ route('shop.search.index') }}"
                class="hidden md:inline-block text-brand-earth hover:text-brand-dark font-medium border-b border-brand-earth hover:border-brand-dark pb-1 transition"
            >
                @lang('beyondary.home.view_all')
            </a>
        </div>

        <x-shop::products.carousel
            title=""
            :src="route('shop.api.products.index', ['limit' => 8, 'sort' => 'created_at-desc'])"
            :navigation-link="route('shop.search.index')"
            aria-label="{{ __('beyondary.home.featured_title') }}"
        />

        <div class="mt-10 text-center md:hidden">
            <a href="{{ route('shop.search.index') }}" class="inline-block text-brand-earth border-b border-brand-earth pb-1 font-medium">
                @lang('beyondary.home.view_all')
            </a>
        </div>
    </div>
</section>
