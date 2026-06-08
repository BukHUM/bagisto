<?php

use Beyondary\Storefront\Services\HomeSectionService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('theme_customizations') || ! Schema::hasTable('theme_customization_translations')) {
            return;
        }

        $beyondaryChannelIds = DB::table('channels')
            ->where('theme', 'beyondary')
            ->pluck('id');

        foreach ($beyondaryChannelIds as $channelId) {
            $exists = DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->where('theme_code', 'beyondary')
                ->where('type', 'static_content')
                ->where('name', HomeSectionService::OUR_STORY_NAME)
                ->exists();

            if ($exists) {
                continue;
            }

            $now = now();
            $id = DB::table('theme_customizations')->insertGetId([
                'type' => 'static_content',
                'name' => HomeSectionService::OUR_STORY_NAME,
                'sort_order' => 5,
                'status' => 1,
                'channel_id' => $channelId,
                'theme_code' => 'beyondary',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $service = app(HomeSectionService::class);

            foreach (['en', 'th'] as $locale) {
                $fields = $service->defaultOurStoryFields($locale);

                DB::table('theme_customization_translations')->insert([
                    'theme_customization_id' => $id,
                    'locale' => $locale,
                    'options' => json_encode([
                        'html' => $service->buildOurStoryHtml($fields),
                        'css' => '',
                    ]),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('theme_customizations')) {
            return;
        }

        $ids = DB::table('theme_customizations')
            ->where('name', HomeSectionService::OUR_STORY_NAME)
            ->where('theme_code', 'beyondary')
            ->pluck('id');

        if ($ids->isEmpty()) {
            return;
        }

        DB::table('theme_customization_translations')->whereIn('theme_customization_id', $ids)->delete();
        DB::table('theme_customizations')->whereIn('id', $ids)->delete();
    }
};
