<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_attribute_values') || ! Schema::hasTable('attributes')) {
            return;
        }

        $attributeId = DB::table('attributes')->where('code', 'url_key')->value('id');

        if (! $attributeId) {
            return;
        }

        $englishValues = DB::table('product_attribute_values')
            ->where('attribute_id', $attributeId)
            ->where('locale', 'en')
            ->whereNotNull('text_value')
            ->where('text_value', '!=', '')
            ->get(['product_id', 'channel', 'text_value']);

        foreach ($englishValues as $englishValue) {
            $updated = DB::table('product_attribute_values')
                ->where('attribute_id', $attributeId)
                ->where('product_id', $englishValue->product_id)
                ->where('channel', $englishValue->channel)
                ->where('locale', 'th')
                ->where(function ($query) {
                    $query->whereNull('text_value')
                        ->orWhere('text_value', '=', '');
                })
                ->update(['text_value' => $englishValue->text_value]);

            if ($updated === 0) {
                $exists = DB::table('product_attribute_values')
                    ->where('attribute_id', $attributeId)
                    ->where('product_id', $englishValue->product_id)
                    ->where('channel', $englishValue->channel)
                    ->where('locale', 'th')
                    ->exists();

                if (! $exists) {
                    DB::table('product_attribute_values')->insert([
                        'product_id' => $englishValue->product_id,
                        'attribute_id' => $attributeId,
                        'channel' => $englishValue->channel,
                        'locale' => 'th',
                        'text_value' => $englishValue->text_value,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        // Non-destructive data backfill — no rollback.
    }
};
