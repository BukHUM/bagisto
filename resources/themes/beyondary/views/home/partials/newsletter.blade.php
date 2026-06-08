@if (core()->getConfigData('customer.settings.newsletter.subscription'))
    <section class="bg-brand-dark py-16 border-y border-[#2A1D13]">
        <div class="container mx-auto px-4 text-center max-w-2xl">
            <i class="fa-regular fa-envelope text-4xl text-brand-gold mb-4" aria-hidden="true"></i>
            <h2 class="font-serif text-3xl text-brand-light font-bold mb-4">
                @lang('beyondary.newsletter.title')
            </h2>
            <p class="text-brand-light/70 mb-8">
                @lang('beyondary.newsletter.desc')
            </p>

            <x-shop::form
                :action="route('shop.subscription.store')"
                class="flex flex-col sm:flex-row gap-2 justify-center"
            >
                <x-shop::form.control-group.control
                    type="email"
                    class="px-6 py-3 bg-brand-light/10 border border-brand-light/20 text-brand-light placeholder-brand-light/50 focus:outline-none focus:border-brand-gold w-full sm:w-2/3 rounded-sm"
                    name="email"
                    rules="required|email"
                    :label="trans('beyondary.newsletter.placeholder')"
                    :placeholder="trans('beyondary.newsletter.placeholder')"
                />

                <button
                    type="submit"
                    class="px-8 py-3 bg-brand-gold hover:bg-[#A07644] text-white font-medium transition whitespace-nowrap rounded-sm"
                >
                    @lang('beyondary.newsletter.subscribe')
                </button>
            </x-shop::form>
        </div>
    </section>
@endif
