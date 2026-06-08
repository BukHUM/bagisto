<?php

namespace Beyondary\Storefront\Services;

use App\Helpers\BeyondaryTheme;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Webkul\Core\Models\Channel;
use Webkul\Theme\Contracts\ThemeCustomization;
use Webkul\Theme\Models\ThemeCustomization as ThemeCustomizationModel;
use Webkul\Theme\Repositories\ThemeCustomizationRepository;

class HomeSectionService
{
    public const OUR_STORY_NAME = 'Beyondary — Our Story';

    public const NAVIGATION_NAME = BeyondaryTheme::NAVIGATION_NAME;

    public const FOOTER_NAME = 'Beyondary — Footer';

    /**
     * @var array<string, array{label: string, type: string, singleton: bool, sort_order: int, default_name: string}>
     */
    public const SECTIONS = [
        'hero' => [
            'label' => 'Hero / Slider',
            'type' => ThemeCustomizationModel::IMAGE_CAROUSEL,
            'singleton' => true,
            'sort_order' => 1,
            'default_name' => 'Beyondary — Hero',
        ],
        'trust' => [
            'label' => 'Trust Badges',
            'type' => ThemeCustomizationModel::SERVICES_CONTENT,
            'singleton' => true,
            'sort_order' => 2,
            'default_name' => 'Beyondary — Trust Badges',
        ],
        'categories' => [
            'label' => 'Categories',
            'type' => ThemeCustomizationModel::CATEGORY_CAROUSEL,
            'singleton' => true,
            'sort_order' => 3,
            'default_name' => 'Beyondary — Categories',
        ],
        'products' => [
            'label' => 'Featured Products',
            'type' => ThemeCustomizationModel::PRODUCT_CAROUSEL,
            'singleton' => true,
            'sort_order' => 4,
            'default_name' => 'Beyondary — Featured Products',
        ],
        'our_story' => [
            'label' => 'Our Story',
            'type' => ThemeCustomizationModel::STATIC_CONTENT,
            'singleton' => true,
            'sort_order' => 5,
            'default_name' => self::OUR_STORY_NAME,
        ],
        'menu' => [
            'label' => 'Header Menu',
            'type' => ThemeCustomizationModel::STATIC_CONTENT,
            'singleton' => true,
            'sort_order' => 20,
            'default_name' => self::NAVIGATION_NAME,
        ],
        'footer' => [
            'label' => 'Footer',
            'type' => ThemeCustomizationModel::FOOTER_LINKS,
            'singleton' => true,
            'sort_order' => 21,
            'default_name' => self::FOOTER_NAME,
        ],
    ];

    public function __construct(
        protected ThemeCustomizationRepository $themeCustomizationRepository
    ) {}

    public function sectionMeta(string $key): array
    {
        if (! isset(self::SECTIONS[$key])) {
            abort(404);
        }

        return self::SECTIONS[$key];
    }

    public function channel(): Channel
    {
        return core()->getCurrentChannel();
    }

    public function resolveSection(string $key, ?int $id = null): ThemeCustomization
    {
        $meta = $this->sectionMeta($key);
        $channel = $this->channel();

        if ($id) {
            $theme = $this->themeCustomizationRepository->findOneWhere([
                'id' => $id,
                'channel_id' => $channel->id,
                'theme_code' => $channel->theme,
                'type' => $meta['type'],
            ]);

            if (! $theme) {
                abort(404);
            }

            return $theme;
        }

        $query = [
            'channel_id' => $channel->id,
            'theme_code' => $channel->theme,
            'type' => $meta['type'],
        ];

        if ($key === 'our_story') {
            $theme = $this->themeCustomizationRepository
                ->findWhere($query)
                ->first(fn ($item) => $item->name === self::OUR_STORY_NAME);

            if ($theme) {
                return $theme;
            }
        } elseif ($key === 'menu') {
            $theme = $this->themeCustomizationRepository
                ->findWhere($query)
                ->first(fn ($item) => $item->name === self::NAVIGATION_NAME);

            if ($theme) {
                return $theme;
            }
        } elseif ($key === 'footer') {
            $theme = $this->themeCustomizationRepository->findOneWhere($query);

            if ($theme) {
                return $theme;
            }
        } else {
            $theme = $this->themeCustomizationRepository->findOneWhere(array_merge($query, [
                'status' => 1,
            ]));

            if ($theme) {
                return $theme;
            }
        }

        return $this->createSection($key);
    }

    public function createSection(string $key): ThemeCustomization
    {
        $meta = $this->sectionMeta($key);
        $channel = $this->channel();

        Event::dispatch('theme_customization.create.before');

        $theme = $this->themeCustomizationRepository->create([
            'name' => $meta['default_name'],
            'type' => $meta['type'],
            'sort_order' => $meta['sort_order'],
            'status' => 1,
            'channel_id' => $channel->id,
            'theme_code' => $channel->theme,
        ]);

        Event::dispatch('theme_customization.create.after', $theme);

        match ($key) {
            'our_story' => $this->seedOurStoryTranslations($theme),
            'menu' => $this->seedNavigationTranslations($theme),
            'footer' => $this->seedFooterTranslations($theme),
            default => null,
        };

        return $theme;
    }

    /**
     * @return Collection<int, ThemeCustomization>
     */
    public function exportableSections(?Channel $channel = null): Collection
    {
        $channel ??= $this->channel();

        return $this->themeCustomizationRepository
            ->orderBy('sort_order')
            ->findWhere([
                'channel_id' => $channel->id,
                'theme_code' => $channel->theme,
            ])
            ->filter(fn ($item) => $this->isManagedSection($item));
    }

    public function isManagedSection(ThemeCustomization $item): bool
    {
        if ($item->type === ThemeCustomizationModel::FOOTER_LINKS) {
            return true;
        }

        if ($item->type === ThemeCustomizationModel::STATIC_CONTENT) {
            return in_array($item->name, [self::OUR_STORY_NAME, self::NAVIGATION_NAME], true);
        }

        return in_array($item->type, [
            ThemeCustomizationModel::IMAGE_CAROUSEL,
            ThemeCustomizationModel::SERVICES_CONTENT,
            ThemeCustomizationModel::CATEGORY_CAROUSEL,
            ThemeCustomizationModel::PRODUCT_CAROUSEL,
        ], true);
    }

    /**
     * @param  array{name?: string, sort_order?: int, status?: bool}  $meta
     */
    public function updateThemeMeta(ThemeCustomization $theme, array $meta): ThemeCustomization
    {
        Event::dispatch('theme_customization.update.before', $theme->id);

        $theme->update([
            'name' => $meta['name'] ?? $theme->name,
            'sort_order' => $meta['sort_order'] ?? $theme->sort_order,
            'status' => $meta['status'] ?? $theme->status,
        ]);

        Event::dispatch('theme_customization.update.after', $theme);

        return $theme;
    }

    public function updateTheme(array $data, int $id): ThemeCustomization
    {
        Event::dispatch('theme_customization.update.before', $id);

        $theme = $this->themeCustomizationRepository->update($data, $id);

        Event::dispatch('theme_customization.update.after', $theme);

        return $theme;
    }

    /**
     * @param  array<int, array{title?: string, link?: string, image?: UploadedFile|string}>  $slides
     */
    public function saveImageCarousel(ThemeCustomization $theme, string $locale, array $slides): void
    {
        $images = [];

        foreach ($slides as $slide) {
            $image = $slide['image'] ?? '';

            if ($image instanceof UploadedFile) {
                $path = 'theme/'.$theme->id.'/'.Str::random(40).'.webp';
                $encoded = image_manager()->read($image)->encodeByExtension('webp');
                Storage::put($path, (string) $encoded);

                $images[] = [
                    'image' => 'storage/'.$path,
                    'link' => $slide['link'] ?? '',
                    'title' => $slide['title'] ?? '',
                ];
            } elseif (is_string($image) && $image !== '') {
                $images[] = [
                    'image' => $image,
                    'link' => $slide['link'] ?? '',
                    'title' => $slide['title'] ?? '',
                ];
            }
        }

        $this->saveTranslationOptions($theme, $locale, ['images' => $images]);
    }

    /**
     * @param  array<int, array{title: string, description: string, service_icon: string}>  $services
     */
    public function saveServicesContent(ThemeCustomization $theme, string $locale, array $services): void
    {
        $this->saveTranslationOptions($theme, $locale, [
            'services' => array_values($services),
        ]);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function saveTranslationOptions(ThemeCustomization $theme, string $locale, array $options): void
    {
        $translatedModel = $theme->translateOrNew($locale);
        $translatedModel->options = $options;
        $translatedModel->theme_customization_id = $theme->id;
        $translatedModel->save();
    }

    /**
     * @param  array<string, string>  $fields
     */
    public function buildOurStoryHtml(array $fields): string
    {
        $image = e($fields['image_url'] ?? asset('themes/shop/beyondary/images/story.jpg'));
        $title = e($fields['title'] ?? '');
        $highlight = e($fields['title_highlight'] ?? '');
        $p1 = e($fields['p1'] ?? '');
        $p2 = e($fields['p2'] ?? '');
        $cta = e($fields['cta'] ?? '');
        $ctaLink = e($fields['cta_link'] ?? '#artisans');

        return <<<HTML
<section id="artisans" class="py-16 md:py-24 bg-brand-light">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-12">
            <div class="w-full lg:w-1/2 aspect-square lg:aspect-[4/3] bg-brand-dark relative overflow-hidden shadow-xl rounded-sm">
                <img src="{$image}" alt="" class="w-full h-full object-cover hover:scale-105 transition duration-700 opacity-90">
            </div>
            <div class="w-full lg:w-1/2 lg:pl-10">
                <h2 class="font-serif text-3xl md:text-5xl text-brand-dark font-bold mb-6 leading-tight">
                    {$title}
                    <span class="text-brand-gold italic">{$highlight}</span>
                </h2>
                <p class="text-brand-dark/70 mb-6 text-lg leading-relaxed">{$p1}</p>
                <p class="text-brand-dark/70 mb-8 leading-relaxed">{$p2}</p>
                <a href="{$ctaLink}" class="inline-flex items-center font-medium text-brand-dark hover:text-brand-gold transition group">
                    {$cta}
                    <i class="fa-solid fa-arrow-right-long ml-2 transform group-hover:translate-x-2 transition" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</section>
HTML;
    }

    /**
     * @return array<string, string>
     */
    public function parseOurStoryFields(ThemeCustomization $theme, string $locale): array
    {
        $defaults = $this->defaultOurStoryFields($locale);
        $html = $theme->translate($locale)->options['html'] ?? '';

        if (preg_match('/src="([^"]+)"/', $html, $image)) {
            $defaults['image_url'] = $image[1];
        }

        if (preg_match('/<h2[^>]*>\s*(.*?)\s*<span[^>]*>(.*?)<\/span>/s', $html, $title)) {
            $defaults['title'] = trim(strip_tags($title[1]));
            $defaults['title_highlight'] = trim(strip_tags($title[2]));
        }

        if (preg_match_all('/<p class="text-brand-dark\/70[^"]*"[^>]*>(.*?)<\/p>/s', $html, $paragraphs)) {
            $defaults['p1'] = trim(strip_tags($paragraphs[1][0] ?? ''));
            $defaults['p2'] = trim(strip_tags($paragraphs[1][1] ?? ''));
        }

        if (preg_match('/<a href="([^"]*)"[^>]*>\s*(.*?)\s*<i/s', $html, $cta)) {
            $defaults['cta_link'] = $cta[1];
            $defaults['cta'] = trim(strip_tags($cta[2]));
        }

        return $defaults;
    }

    /**
     * @return array<string, string>
     */
    public function defaultOurStoryFields(string $locale): array
    {
        return [
            'image_url' => asset('themes/shop/beyondary/images/story.jpg'),
            'title' => trans('beyondary.story.title', [], $locale),
            'title_highlight' => trans('beyondary.story.title_highlight', [], $locale),
            'p1' => trans('beyondary.story.p1', [], $locale),
            'p2' => trans('beyondary.story.p2', [], $locale),
            'cta' => trans('beyondary.story.cta', [], $locale),
            'cta_link' => '#artisans',
        ];
    }

    public function storeOurStoryImage(UploadedFile $file, ThemeCustomization $theme): string
    {
        $path = 'theme/'.$theme->id.'/'.Str::random(40).'.webp';
        $encoded = image_manager()->read($file)->encodeByExtension('webp');
        Storage::put($path, (string) $encoded);

        return asset('storage/'.$path);
    }

    protected function seedOurStoryTranslations(ThemeCustomization $theme): void
    {
        foreach (core()->getAllLocales() as $locale) {
            $code = $locale->code;
            $model = $theme->translateOrNew($code);
            $model->options = [
                'html' => $this->buildOurStoryHtml($this->defaultOurStoryFields($code)),
                'css' => '',
            ];
            $model->theme_customization_id = $theme->id;
            $model->save();
        }
    }

    protected function seedNavigationTranslations(ThemeCustomization $theme): void
    {
        foreach (core()->getAllLocales() as $locale) {
            $this->saveNavigationContent($theme, $locale->code, $this->defaultNavigationFields($locale->code));
        }
    }

    protected function seedFooterTranslations(ThemeCustomization $theme): void
    {
        foreach (core()->getAllLocales() as $locale) {
            $this->saveFooterContent($theme, $locale->code, $this->defaultFooterFields($locale->code));
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function defaultNavigationFields(string $locale): array
    {
        return BeyondaryTheme::defaultNavigationFields($locale);
    }

    /**
     * @return array<string, mixed>
     */
    public function defaultFooterFields(string $locale): array
    {
        return BeyondaryTheme::defaultFooterFields($locale);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function saveNavigationContent(ThemeCustomization $theme, string $locale, array $options): void
    {
        $options['html'] = $options['html'] ?? '';
        $options['css'] = $options['css'] ?? '';

        $this->saveTranslationOptions($theme, $locale, $options);
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function saveFooterContent(ThemeCustomization $theme, string $locale, array $options): void
    {
        $this->saveTranslationOptions($theme, $locale, $options);
    }
}
