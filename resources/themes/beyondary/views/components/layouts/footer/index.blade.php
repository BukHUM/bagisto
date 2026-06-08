{!! view_render_event('bagisto.shop.layout.footer.before') !!}

@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')
@inject('categoryRepository', 'Webkul\Category\Repositories\CategoryRepository')

@php
    use Beyondary\Storefront\Services\HomeSectionService;

    $channel = core()->getCurrentChannel();
    $locale = app()->getLocale();

    $footerLinks = $themeCustomizationRepository->findOneWhere([
        'type' => 'footer_links',
        'status' => 1,
        'theme_code' => $channel->theme,
        'channel_id' => $channel->id,
    ]);

    $footerOptions = $footerLinks?->translate($locale)?->options ?? [];
    $defaultFooter = app(HomeSectionService::class)->defaultFooterFields($locale);

    $footerAbout = $footerOptions['about'] ?? $defaultFooter['about'];
    $footerSocial = array_merge($defaultFooter['social'], $footerOptions['social'] ?? []);
    $supportLinks = $footerOptions['column_2'] ?? $defaultFooter['column_2'];

    usort($supportLinks, fn ($a, $b) => ($a['sort_order'] ?? 0) <=> ($b['sort_order'] ?? 0));

    $rootCategories = collect($categoryRepository->getVisibleCategoryTree($channel->root_category_id))->take(5);
@endphp

<footer id="contact" class="bg-brand-light pt-16 pb-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <div>
                <x-shop::layouts.partials.logo
                    id="fallback-logo-footer"
                    image-class="h-10 md:h-12"
                    class="inline-block mb-4"
                />

                <p class="text-brand-dark/70 text-sm mb-6 leading-relaxed">
                    {{ $footerAbout }}
                </p>

                <div class="flex space-x-4">
                    <a href="{{ $footerSocial['facebook'] ?? '#' }}" class="w-8 h-8 rounded-full bg-white border border-[#EAE5DA] flex items-center justify-center text-brand-dark/60 hover:bg-brand-gold hover:text-white hover:border-brand-gold transition" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    <a href="{{ $footerSocial['instagram'] ?? '#' }}" class="w-8 h-8 rounded-full bg-white border border-[#EAE5DA] flex items-center justify-center text-brand-dark/60 hover:bg-brand-gold hover:text-white hover:border-brand-gold transition" aria-label="Instagram">
                        <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="{{ $footerSocial['pinterest'] ?? '#' }}" class="w-8 h-8 rounded-full bg-white border border-[#EAE5DA] flex items-center justify-center text-brand-dark/60 hover:bg-brand-gold hover:text-white hover:border-brand-gold transition" aria-label="Pinterest">
                        <i class="fa-brands fa-pinterest-p" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div>
                <h4 class="font-medium text-brand-dark mb-5 uppercase tracking-wide text-sm">
                    @lang('beyondary.footer.shop')
                </h4>
                <ul class="space-y-3 text-sm text-brand-dark/70">
                    <li>
                        <a href="{{ route('shop.search.index') }}" class="hover:text-brand-gold transition">
                            @lang('beyondary.home.view_all')
                        </a>
                    </li>
                    @foreach ($rootCategories as $category)
                        <li>
                            <a href="{{ $category->url }}" class="hover:text-brand-gold transition">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="font-medium text-brand-dark mb-5 uppercase tracking-wide text-sm">
                    @lang('beyondary.footer.support')
                </h4>
                <ul class="space-y-3 text-sm text-brand-dark/70">
                    @forelse ($supportLinks as $link)
                        <li>
                            <a href="{{ $link['url'] }}" class="hover:text-brand-gold transition">
                                {{ $link['title'] }}
                            </a>
                        </li>
                    @empty
                        <li>
                            <a href="{{ route('shop.home.index') }}#contact" class="hover:text-brand-gold transition">
                                @lang('beyondary.nav.contact')
                            </a>
                        </li>
                    @endforelse
                </ul>
            </div>

            <div>
                <h4 class="font-medium text-brand-dark mb-5 uppercase tracking-wide text-sm">
                    @lang('beyondary.footer.payment_title')
                </h4>
                <p class="text-sm text-brand-dark/70 mb-4">
                    @lang('beyondary.footer.payment_desc')
                </p>
                <div class="flex flex-wrap gap-2 text-2xl text-brand-dark/40">
                    <i class="fa-brands fa-cc-visa hover:text-[#1434CB] transition cursor-pointer" aria-hidden="true"></i>
                    <i class="fa-brands fa-cc-mastercard hover:text-[#EB001B] transition cursor-pointer" aria-hidden="true"></i>
                    <i class="fa-brands fa-cc-paypal hover:text-[#003087] transition cursor-pointer" aria-hidden="true"></i>
                    <i class="fa-brands fa-cc-amex hover:text-[#2E77BB] transition cursor-pointer" aria-hidden="true"></i>
                    <i class="fa-brands fa-alipay hover:text-[#00A1E9] transition cursor-pointer" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        <div class="border-t border-[#EAE5DA] pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-brand-dark/50">
            <p>
                @if (core()->getConfigData('general.content.footer.copyright_content'))
                    {!! core()->getConfigData('general.content.footer.copyright_content') !!}
                @else
                    &copy; {{ date('Y') }} @lang('beyondary.footer.copyright')
                @endif
            </p>
        </div>
    </div>
</footer>

{!! view_render_event('bagisto.shop.layout.footer.after') !!}
