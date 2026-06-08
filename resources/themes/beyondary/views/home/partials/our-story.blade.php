<section id="artisans" class="py-16 md:py-24 bg-brand-light">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 aspect-square lg:aspect-[4/3] bg-brand-dark relative overflow-hidden shadow-xl rounded-sm">
                <img
                    src="{{ asset('themes/shop/beyondary/images/story.jpg') }}"
                    alt=""
                    class="w-full h-full object-cover hover:scale-105 transition duration-700 opacity-90"
                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1533626154371-12c85b7b68db?q=80&w=1000&auto=format&fit=crop';"
                >
            </div>
            <div class="w-full lg:w-1/2 lg:pl-10">
                <h2 class="font-serif text-3xl md:text-5xl text-brand-dark font-bold mb-6 leading-tight">
                    @lang('beyondary.story.title')
                    <span class="text-brand-gold italic">@lang('beyondary.story.title_highlight')</span>
                </h2>
                <p class="text-brand-dark/70 mb-6 text-lg leading-relaxed">
                    @lang('beyondary.story.p1')
                </p>
                <p class="text-brand-dark/70 mb-8 leading-relaxed">
                    @lang('beyondary.story.p2')
                </p>
                <a
                    href="{{ route('shop.home.index') }}#artisans"
                    class="inline-flex items-center font-medium text-brand-dark hover:text-brand-gold transition group"
                >
                    @lang('beyondary.story.cta')
                    <i class="fa-solid fa-arrow-right-long ml-2 transform group-hover:translate-x-2 transition" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</section>
