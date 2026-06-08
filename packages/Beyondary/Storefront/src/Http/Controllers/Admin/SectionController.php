<?php

namespace Beyondary\Storefront\Http\Controllers\Admin;

use Beyondary\Storefront\Services\HomeSectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Theme\Contracts\ThemeCustomization;
use Webkul\Theme\Models\ThemeCustomization as ThemeCustomizationModel;

class SectionController extends Controller
{
    public function __construct(
        protected HomeSectionService $homeSectionService
    ) {}

    public function edit(string $section): View
    {
        $theme = $this->homeSectionService->resolveSection($section);
        $channel = $this->homeSectionService->channel();
        $currentLocale = core()->getRequestedLocale();

        return view('beyondary-storefront::admin.sections.edit', [
            'section' => $section,
            'sectionMeta' => $this->homeSectionService->sectionMeta($section),
            'theme' => $theme,
            'channel' => $channel,
            'currentLocale' => $currentLocale,
            'formPartial' => 'beyondary-storefront::admin.sections.forms.'.$section,
            'backUrl' => route('admin.beyondary.storefront.index'),
        ]);
    }

    public function update(string $section): RedirectResponse
    {
        $theme = $this->homeSectionService->resolveSection($section);
        $channel = $this->homeSectionService->channel();
        $locale = request('locale', core()->getRequestedLocaleCode());

        $base = [
            'locale' => $locale,
            'type' => $theme->type,
            'name' => $theme->name,
            'sort_order' => $theme->sort_order,
            'channel_id' => $channel->id,
            'theme_code' => $channel->theme,
            'status' => request()->input('status', 'on') === 'on',
        ];

        match ($section) {
            'hero' => $this->saveHero($theme, $base, $locale),
            'trust' => $this->saveTrust($theme, $base, $locale),
            'categories' => $this->homeSectionService->updateTheme(
                $this->categoriesPayload($base, $locale),
                $theme->id
            ),
            'products' => $this->homeSectionService->updateTheme(
                $this->productsPayload($base, $locale),
                $theme->id
            ),
            'our_story' => $this->homeSectionService->updateTheme(
                $this->ourStoryPayload($base, $locale, $theme),
                $theme->id
            ),
            'menu' => $this->saveMenu($theme, $base, $locale),
            'footer' => $this->saveFooter($theme, $base, $locale),
            default => abort(404),
        };

        session()->flash('success', trans('beyondary-storefront::app.edit.saved'));

        return redirect()->route('admin.beyondary.storefront.sections.edit', $section);
    }

    /**
     * @param  array<string, mixed>  $base
     */
    protected function saveHero(ThemeCustomization $theme, array $base, string $locale): void
    {
        request()->validate([
            'slides' => 'array',
            'slides.*.title' => 'nullable|string|max:255',
            'slides.*.link' => 'nullable|string|max:255',
            'slides.*.image' => 'nullable|image|extensions:jpeg,jpg,png,webp,svg',
        ]);

        $slides = [];

        foreach (request()->input('slides', []) as $index => $slide) {
            $slides[] = [
                'title' => $slide['title'] ?? '',
                'link' => $slide['link'] ?? '',
                'image' => request()->file("slides.{$index}.image")
                    ?? ($slide['existing_image'] ?? ''),
            ];
        }

        $this->homeSectionService->saveImageCarousel($theme, $locale, $slides);
        $this->homeSectionService->updateThemeMeta($theme, [
            'status' => $base['status'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $base
     */
    protected function saveTrust(ThemeCustomization $theme, array $base, string $locale): void
    {
        request()->validate([
            'services' => 'required|array|min:1|max:4',
            'services.*.title' => 'required|string|max:255',
            'services.*.description' => 'required|string|max:500',
            'services.*.service_icon' => 'required|string|max:50',
        ]);

        $this->homeSectionService->saveServicesContent($theme, $locale, request()->input('services', []));
        $this->homeSectionService->updateThemeMeta($theme, [
            'status' => $base['status'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $base
     * @return array<string, mixed>
     */
    protected function categoriesPayload(array $base, string $locale): array
    {
        request()->validate([
            'title' => 'required|string|max:255',
            'sort' => 'required|string|max:50',
            'limit' => 'required|integer|min:1|max:20',
        ]);

        $base[$locale]['options'] = [
            'title' => request('title'),
            'filters' => [
                'parent_id' => 1,
                'sort' => request('sort'),
                'limit' => (int) request('limit'),
            ],
        ];

        return $base;
    }

    /**
     * @param  array<string, mixed>  $base
     * @return array<string, mixed>
     */
    protected function productsPayload(array $base, string $locale): array
    {
        request()->validate([
            'title' => 'required|string|max:255',
            'sort' => 'required|string|max:50',
            'limit' => 'required|integer|min:1|max:20',
            'category_id' => 'nullable|integer',
        ]);

        $filters = [
            'sort' => request('sort'),
            'limit' => (int) request('limit'),
        ];

        if (request()->filled('category_id')) {
            $filters['category_id'] = (int) request('category_id');
        }

        $base[$locale]['options'] = [
            'title' => request('title'),
            'filters' => $filters,
        ];

        return $base;
    }

    /**
     * @param  array<string, mixed>  $base
     * @return array<string, mixed>
     */
    protected function ourStoryPayload(array $base, string $locale, ThemeCustomization $theme): array
    {
        request()->validate([
            'title' => 'required|string|max:255',
            'title_highlight' => 'required|string|max:255',
            'p1' => 'required|string|max:2000',
            'p2' => 'required|string|max:2000',
            'cta' => 'required|string|max:255',
            'cta_link' => 'required|string|max:255',
            'image_url' => 'nullable|string|max:500',
            'image' => 'nullable|image|extensions:jpeg,jpg,png,webp',
        ]);

        $fields = [
            'title' => request('title'),
            'title_highlight' => request('title_highlight'),
            'p1' => request('p1'),
            'p2' => request('p2'),
            'cta' => request('cta'),
            'cta_link' => request('cta_link'),
            'image_url' => request('image_url'),
        ];

        if ($image = request()->file('image')) {
            $fields['image_url'] = $this->homeSectionService->storeOurStoryImage($image, $theme);
        }

        $base['type'] = ThemeCustomizationModel::STATIC_CONTENT;
        $base[$locale]['options'] = [
            'html' => $this->homeSectionService->buildOurStoryHtml($fields),
            'css' => '',
        ];

        return $base;
    }

    /**
     * @param  array<string, mixed>  $base
     */
    protected function saveMenu(ThemeCustomization $theme, array $base, string $locale): void
    {
        request()->validate([
            'announcement' => 'required|string|max:500',
            'links' => 'required|array|min:1|max:8',
            'links.*.title' => 'required|string|max:255',
            'links.*.url' => 'required|string|max:500',
            'links.*.sort_order' => 'nullable|integer|min:0',
        ]);

        $links = collect(request()->input('links', []))
            ->values()
            ->map(fn ($link, $index) => [
                'title' => $link['title'],
                'url' => $link['url'],
                'sort_order' => (int) ($link['sort_order'] ?? ($index + 1)),
            ])
            ->all();

        $this->homeSectionService->saveNavigationContent($theme, $locale, [
            'announcement' => request('announcement'),
            'links' => $links,
            'html' => '',
            'css' => '',
        ]);

        $this->homeSectionService->updateThemeMeta($theme, [
            'status' => $base['status'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $base
     */
    protected function saveFooter(ThemeCustomization $theme, array $base, string $locale): void
    {
        request()->validate([
            'about' => 'required|string|max:2000',
            'social_facebook' => 'nullable|string|max:500',
            'social_instagram' => 'nullable|string|max:500',
            'social_pinterest' => 'nullable|string|max:500',
            'links' => 'array',
            'links.*.title' => 'required_with:links.*.url|string|max:255',
            'links.*.url' => 'nullable|string|max:500',
        ]);

        $existing = $theme->translate($locale)?->options ?? [];
        $links = [];

        foreach (request()->input('links', []) as $link) {
            if (empty($link['title']) && empty($link['url'])) {
                continue;
            }

            $links[] = [
                'title' => $link['title'],
                'url' => $link['url'],
                'sort_order' => count($links) + 1,
            ];
        }

        $this->homeSectionService->saveFooterContent($theme, $locale, [
            'about' => request('about'),
            'social' => [
                'facebook' => request('social_facebook', '#'),
                'instagram' => request('social_instagram', '#'),
                'pinterest' => request('social_pinterest', '#'),
            ],
            'column_1' => $existing['column_1'] ?? [],
            'column_2' => $links,
        ]);

        $this->homeSectionService->updateThemeMeta($theme, [
            'status' => $base['status'],
        ]);
    }
}
