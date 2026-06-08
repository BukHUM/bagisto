<?php

namespace App\Helpers;

class BeyondaryTheme
{
    public const NAVIGATION_NAME = 'Beyondary — Navigation';

    /**
     * @return array<string, mixed>
     */
    public static function defaultNavigationFields(string $locale): array
    {
        return [
            'announcement' => trans('beyondary.announcement', [], $locale),
            'links' => [
                ['title' => trans('beyondary.nav.home', [], $locale), 'url' => '/', 'sort_order' => 1],
                ['title' => trans('beyondary.nav.shop', [], $locale), 'url' => '/search', 'sort_order' => 2],
                ['title' => trans('beyondary.nav.categories', [], $locale), 'url' => '/#categories', 'sort_order' => 3],
                ['title' => trans('beyondary.nav.story', [], $locale), 'url' => '/#artisans', 'sort_order' => 4],
                ['title' => trans('beyondary.nav.contact', [], $locale), 'url' => '/#contact', 'sort_order' => 5],
            ],
            'html' => '',
            'css' => '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultFooterFields(string $locale): array
    {
        $baseUrl = rtrim(config('app.url'), '/');

        return [
            'about' => trans('beyondary.footer.about', [], $locale),
            'social' => [
                'facebook' => '#',
                'instagram' => '#',
                'pinterest' => '#',
            ],
            'column_1' => [],
            'column_2' => [
                ['url' => $baseUrl.'/page/about-us', 'title' => trans('beyondary.nav.contact', [], $locale), 'sort_order' => 1],
                ['url' => $baseUrl.'/contact-us', 'title' => 'Contact Us', 'sort_order' => 2],
            ],
        ];
    }
}
