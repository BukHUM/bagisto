<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('product_flat')) {
            return;
        }

        $pairs = DB::table('product_flat as th')
            ->join('product_flat as en', function ($join) {
                $join->on('th.product_id', '=', 'en.product_id')
                    ->on('th.channel', '=', 'en.channel');
            })
            ->where('en.locale', 'en')
            ->where('th.locale', '!=', 'en')
            ->whereNotNull('en.url_key')
            ->where('en.url_key', '!=', '')
            ->where(function ($query) {
                $query->whereNull('th.url_key')
                    ->orWhere('th.url_key', '=', '');
            })
            ->select([
                'th.id',
                'en.url_key as source_url_key',
            ])
            ->get();

        foreach ($pairs as $row) {
            DB::table('product_flat')
                ->where('id', $row->id)
                ->update(['url_key' => $row->source_url_key]);
        }
    }

    public function down(): void
    {
        // Non-destructive data backfill — no rollback.
    }
};
