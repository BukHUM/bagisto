<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\CMS\Models\Page;
use Webkul\Core\Models\Channel;

class CmsPagesTableSeeder extends Seeder
{
    /**
     * Seed or update CMS pages with full en/th content.
     */
    public function run(): void
    {
        $channelIds = Channel::query()->pluck('id')->all();

        if ($channelIds === []) {
            $this->command?->warn('No channels found. Run channel seeders first.');

            return;
        }

        /** @var array<string, array<string, array{page_title: string, meta_title: string, meta_description: string, meta_keywords: string, html_content: string}>> $pages */
        $pages = require database_path('seeders/data/cms_pages.php');

        foreach ($pages as $urlKey => $translations) {
            $cmsPage = Page::whereTranslation('url_key', $urlKey)->first();

            if (! $cmsPage) {
                $cmsPage = Page::create(['layout' => null]);
                $this->command?->info("Created page [{$urlKey}].");
            } else {
                $this->command?->info("Updated page [{$urlKey}].");
            }

            foreach ($translations as $locale => $fields) {
                $cmsPage->translations()->updateOrCreate(
                    [
                        'locale' => $locale,
                        'cms_page_id' => $cmsPage->id,
                    ],
                    [
                        'url_key' => $urlKey,
                        'page_title' => $fields['page_title'],
                        'html_content' => $fields['html_content'],
                        'meta_title' => $fields['meta_title'],
                        'meta_description' => $fields['meta_description'],
                        'meta_keywords' => $fields['meta_keywords'],
                    ]
                );
            }

            $cmsPage->channels()->syncWithoutDetaching($channelIds);
        }
    }
}
