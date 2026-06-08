<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Seed beyondary homepage carousel sections and tidy default static blocks.
     */
    public function up(): void
    {
        if (! Schema::hasTable('theme_customizations') || ! Schema::hasTable('theme_customization_translations')) {
            return;
        }

        $beyondaryChannelIds = DB::table('channels')
            ->where('theme', 'beyondary')
            ->pluck('id');

        if ($beyondaryChannelIds->isEmpty()) {
            return;
        }

        $now = now();

        foreach ($beyondaryChannelIds as $channelId) {
            DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->where('type', 'static_content')
                ->where('sort_order', 2)
                ->update([
                    'status' => 0,
                    'updated_at' => $now,
                ]);

            DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->where('type', 'services_content')
                ->where('theme_code', 'beyondary')
                ->update([
                    'sort_order' => 2,
                    'updated_at' => $now,
                ]);

            $hasCategoryCarousel = DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->where('theme_code', 'beyondary')
                ->where('type', 'category_carousel')
                ->exists();

            if (! $hasCategoryCarousel) {
                $categoryId = DB::table('theme_customizations')->insertGetId([
                    'type' => 'category_carousel',
                    'name' => 'Beyondary — Categories',
                    'sort_order' => 3,
                    'status' => 1,
                    'channel_id' => $channelId,
                    'theme_code' => 'beyondary',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $this->insertTranslations($categoryId, [
                    'en' => [
                        'title' => 'Shop by Category',
                        'filters' => [
                            'parent_id' => 1,
                            'sort' => 'asc',
                            'limit' => 10,
                        ],
                    ],
                    'th' => [
                        'title' => 'หมวดหมู่สินค้า Beyondary',
                        'filters' => [
                            'parent_id' => 1,
                            'sort' => 'asc',
                            'limit' => 10,
                        ],
                    ],
                ]);
            }

            $hasProductCarousel = DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->where('theme_code', 'beyondary')
                ->where('type', 'product_carousel')
                ->exists();

            if (! $hasProductCarousel) {
                $productId = DB::table('theme_customizations')->insertGetId([
                    'type' => 'product_carousel',
                    'name' => 'Beyondary — Featured Products',
                    'sort_order' => 4,
                    'status' => 1,
                    'channel_id' => $channelId,
                    'theme_code' => 'beyondary',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                $this->insertTranslations($productId, [
                    'en' => [
                        'title' => 'Latest Collection',
                        'filters' => [
                            'sort' => 'created_at-desc',
                            'limit' => 8,
                        ],
                    ],
                    'th' => [
                        'title' => 'คอลเลกชันใหม่ล่าสุด',
                        'filters' => [
                            'sort' => 'created_at-desc',
                            'limit' => 8,
                        ],
                    ],
                ]);
            }
        }
    }

    /**
     * @param  array<string, array<string, mixed>>  $localeOptions
     */
    protected function insertTranslations(int $customizationId, array $localeOptions): void
    {
        $rows = [];

        foreach ($localeOptions as $locale => $options) {
            $rows[] = [
                'theme_customization_id' => $customizationId,
                'locale' => $locale,
                'options' => json_encode($options),
            ];
        }

        DB::table('theme_customization_translations')->insert($rows);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('theme_customizations')) {
            return;
        }

        $beyondaryChannelIds = DB::table('channels')
            ->where('theme', 'beyondary')
            ->pluck('id');

        foreach ($beyondaryChannelIds as $channelId) {
            $seededIds = DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->where('theme_code', 'beyondary')
                ->whereIn('name', [
                    'Beyondary — Categories',
                    'Beyondary — Featured Products',
                ])
                ->pluck('id');

            if ($seededIds->isNotEmpty()) {
                DB::table('theme_customization_translations')
                    ->whereIn('theme_customization_id', $seededIds)
                    ->delete();

                DB::table('theme_customizations')
                    ->whereIn('id', $seededIds)
                    ->delete();
            }

            DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->where('type', 'static_content')
                ->where('sort_order', 2)
                ->update(['status' => 1]);
        }
    }
};
