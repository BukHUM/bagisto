<section class="relative w-full h-[60vh] md:h-[80vh] flex items-center justify-center bg-brand-dark">
    <div class="absolute inset-0 bg-brand-dark">
        <img
            src="{{ asset('themes/shop/beyondary/images/hero.jpg') }}"
            alt=""
            class="w-full h-full object-cover opacity-50 mix-blend-overlay"
            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1606760227091-3dd870d97f1d?q=80&w=2000&auto=format&fit=crop';"
        >
    </div>

    <div class="relative z-10 text-center px-4 max-w-3xl mx-auto mt-8 md:mt-0">
        <span class="text-brand-gold font-sans tracking-widest uppercase text-sm md:text-base font-semibold mb-4 block">
            @lang('beyondary.hero.tagline')
        </span>
        <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl text-white font-bold mb-6 leading-tight">
            {!! nl2br(e(__('beyondary.hero.title'))) !!}
        </h1>
        <p class="text-brand-light/90 text-base md:text-lg mb-8 font-light max-w-xl mx-auto">
            @lang('beyondary.hero.subtitle')
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a
                href="{{ route('shop.search.index') }}"
                class="bg-brand-gold hover:bg-[#A07644] text-white px-8 py-3 rounded-none font-medium tracking-wide transition duration-300"
            >
                @lang('beyondary.hero.cta_shop')
            </a>
            <a
                href="{{ route('shop.home.index') }}#artisans"
                class="bg-transparent border border-brand-light text-brand-light hover:bg-brand-light hover:text-brand-dark px-8 py-3 rounded-none font-medium tracking-wide transition duration-300"
            >
                @lang('beyondary.hero.cta_story')
            </a>
        </div>
    </div>
</section>
