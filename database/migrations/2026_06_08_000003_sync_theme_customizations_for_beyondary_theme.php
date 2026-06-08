<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Point installer theme customizations at the active beyondary channel theme.
     */
    public function up(): void
    {
        if (! Schema::hasTable('theme_customizations') || ! Schema::hasTable('channels')) {
            return;
        }

        $beyondaryChannelIds = DB::table('channels')
            ->where('theme', 'beyondary')
            ->pluck('id');

        if ($beyondaryChannelIds->isEmpty()) {
            return;
        }

        foreach ($beyondaryChannelIds as $channelId) {
            DB::table('theme_customizations')
                ->where('channel_id', $channelId)
                ->whereIn('type', [
                    'image_carousel',
                    'services_content',
                    'category_carousel',
                    'product_carousel',
                    'footer_links',
                ])
                ->where(function ($query) {
                    $query->whereNull('theme_code')
                        ->orWhere('theme_code', 'default');
                })
                ->update([
                    'theme_code' => 'beyondary',
                    'updated_at' => now(),
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('theme_customizations') || ! Schema::hasTable('channels')) {
            return;
        }

        $beyondaryChannelIds = DB::table('channels')
            ->where('theme', 'beyondary')
            ->pluck('id');

        if ($beyondaryChannelIds->isEmpty()) {
            return;
        }

        DB::table('theme_customizations')
            ->whereIn('channel_id', $beyondaryChannelIds)
            ->where('theme_code', 'beyondary')
            ->update([
                'theme_code' => 'default',
                'updated_at' => now(),
            ]);
    }
};
