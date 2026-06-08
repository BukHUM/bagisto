<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasIndex('orders', 'orders_status_idx')) {
                $table->index('status', 'orders_status_idx');
            }

            if (! Schema::hasIndex('orders', 'orders_created_at_idx')) {
                $table->index('created_at', 'orders_created_at_idx');
            }

            if (! Schema::hasIndex('orders', 'orders_customer_email_idx')) {
                $table->index('customer_email', 'orders_customer_email_idx');
            }

            if (! Schema::hasIndex('orders', 'orders_customer_created_idx')) {
                $table->index(['customer_id', 'created_at'], 'orders_customer_created_idx');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (! Schema::hasIndex('order_items', 'order_items_product_id_idx')) {
                $table->index('product_id', 'order_items_product_id_idx');
            }
        });

        Schema::table('product_flat', function (Blueprint $table) {
            if (! Schema::hasIndex('product_flat', 'product_flat_status_idx')) {
                $table->index('status', 'product_flat_status_idx');
            }

            if (! Schema::hasIndex('product_flat', 'product_flat_visible_idx')) {
                $table->index('visible_individually', 'product_flat_visible_idx');
            }

            if (! Schema::hasIndex('product_flat', 'product_flat_url_key_idx')) {
                $table->index('url_key', 'product_flat_url_key_idx');
            }

            if (! Schema::hasIndex('product_flat', 'product_flat_parent_id_idx')) {
                $table->index('parent_id', 'product_flat_parent_id_idx');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasIndex('products', 'products_type_idx')) {
                $table->index('type', 'products_type_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasIndex('orders', 'orders_status_idx')) {
                $table->dropIndex('orders_status_idx');
            }

            if (Schema::hasIndex('orders', 'orders_created_at_idx')) {
                $table->dropIndex('orders_created_at_idx');
            }

            if (Schema::hasIndex('orders', 'orders_customer_email_idx')) {
                $table->dropIndex('orders_customer_email_idx');
            }

            if (Schema::hasIndex('orders', 'orders_customer_created_idx')) {
                $table->dropIndex('orders_customer_created_idx');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasIndex('order_items', 'order_items_product_id_idx')) {
                $table->dropIndex('order_items_product_id_idx');
            }
        });

        Schema::table('product_flat', function (Blueprint $table) {
            if (Schema::hasIndex('product_flat', 'product_flat_status_idx')) {
                $table->dropIndex('product_flat_status_idx');
            }

            if (Schema::hasIndex('product_flat', 'product_flat_visible_idx')) {
                $table->dropIndex('product_flat_visible_idx');
            }

            if (Schema::hasIndex('product_flat', 'product_flat_url_key_idx')) {
                $table->dropIndex('product_flat_url_key_idx');
            }

            if (Schema::hasIndex('product_flat', 'product_flat_parent_id_idx')) {
                $table->dropIndex('product_flat_parent_id_idx');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasIndex('products', 'products_type_idx')) {
                $table->dropIndex('products_type_idx');
            }
        });
    }
};
