<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('core_config', function (Blueprint $table) {
            if (! Schema::hasIndex('core_config', 'core_config_code_idx')) {
                $table->index('code', 'core_config_code_idx');
            }

            if (! Schema::hasIndex('core_config', 'core_config_lookup_idx')) {
                $table->index(['code', 'channel_code', 'locale_code'], 'core_config_lookup_idx');
            }
        });

        Schema::table('addresses', function (Blueprint $table) {
            if (! Schema::hasIndex('addresses', 'addresses_order_type_idx')) {
                $table->index(['order_id', 'address_type'], 'addresses_order_type_idx');
            }

            if (! Schema::hasIndex('addresses', 'addresses_customer_type_idx')) {
                $table->index(['customer_id', 'address_type'], 'addresses_customer_type_idx');
            }
        });

        Schema::table('cart', function (Blueprint $table) {
            if (! Schema::hasIndex('cart', 'cart_customer_active_idx')) {
                $table->index(['customer_id', 'is_active'], 'cart_customer_active_idx');
            }
        });

        Schema::table('search_terms', function (Blueprint $table) {
            if (! Schema::hasIndex('search_terms', 'search_terms_term_channel_locale_idx')) {
                $table->unique(['term', 'channel_id', 'locale'], 'search_terms_term_channel_locale_idx');
            }
        });

        Schema::table('product_flat', function (Blueprint $table) {
            if (! Schema::hasIndex('product_flat', 'product_flat_channel_locale_idx')) {
                $table->index(['channel', 'locale'], 'product_flat_channel_locale_idx');
            }

            if (! Schema::hasIndex('product_flat', 'product_flat_listing_idx')) {
                $table->index(['channel', 'locale', 'status', 'visible_individually'], 'product_flat_listing_idx');
            }
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            if (! Schema::hasIndex('product_attribute_values', 'pav_attr_product_idx')) {
                $table->index(['attribute_id', 'product_id'], 'pav_attr_product_idx');
            }
        });

        if (! Schema::hasIndex('product_attribute_values', 'pav_text_value_fulltext')) {
            DB::statement('ALTER TABLE product_attribute_values ADD FULLTEXT INDEX pav_text_value_fulltext (text_value)');
        }

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasIndex('invoices', 'invoices_created_at_idx')) {
                $table->index('created_at', 'invoices_created_at_idx');
            }

            if (! Schema::hasIndex('invoices', 'invoices_state_idx')) {
                $table->index('state', 'invoices_state_idx');
            }
        });

        Schema::table('refunds', function (Blueprint $table) {
            if (! Schema::hasIndex('refunds', 'refunds_created_at_idx')) {
                $table->index('created_at', 'refunds_created_at_idx');
            }

            if (! Schema::hasIndex('refunds', 'refunds_state_idx')) {
                $table->index('state', 'refunds_state_idx');
            }
        });

        Schema::table('shipments', function (Blueprint $table) {
            if (! Schema::hasIndex('shipments', 'shipments_created_at_idx')) {
                $table->index('created_at', 'shipments_created_at_idx');
            }
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            if (! Schema::hasIndex('product_reviews', 'product_reviews_status_idx')) {
                $table->index('status', 'product_reviews_status_idx');
            }

            if (! Schema::hasIndex('product_reviews', 'product_reviews_created_at_idx')) {
                $table->index('created_at', 'product_reviews_created_at_idx');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (! Schema::hasIndex('customers', 'customers_status_idx')) {
                $table->index('status', 'customers_status_idx');
            }

            if (! Schema::hasIndex('customers', 'customers_created_at_idx')) {
                $table->index('created_at', 'customers_created_at_idx');
            }
        });

        Schema::table('wishlist_items', function (Blueprint $table) {
            if (! Schema::hasIndex('wishlist_items', 'wishlist_items_customer_product_idx')) {
                $table->index(['customer_id', 'product_id'], 'wishlist_items_customer_product_idx');
            }
        });

        Schema::table('compare_items', function (Blueprint $table) {
            if (! Schema::hasIndex('compare_items', 'compare_items_customer_product_idx')) {
                $table->index(['customer_id', 'product_id'], 'compare_items_customer_product_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasIndex('product_attribute_values', 'pav_text_value_fulltext')) {
            DB::statement('ALTER TABLE product_attribute_values DROP INDEX pav_text_value_fulltext');
        }

        Schema::table('compare_items', function (Blueprint $table) {
            if (Schema::hasIndex('compare_items', 'compare_items_customer_product_idx')) {
                $table->dropIndex('compare_items_customer_product_idx');
            }
        });

        Schema::table('wishlist_items', function (Blueprint $table) {
            if (Schema::hasIndex('wishlist_items', 'wishlist_items_customer_product_idx')) {
                $table->dropIndex('wishlist_items_customer_product_idx');
            }
        });

        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasIndex('customers', 'customers_status_idx')) {
                $table->dropIndex('customers_status_idx');
            }

            if (Schema::hasIndex('customers', 'customers_created_at_idx')) {
                $table->dropIndex('customers_created_at_idx');
            }
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            if (Schema::hasIndex('product_reviews', 'product_reviews_status_idx')) {
                $table->dropIndex('product_reviews_status_idx');
            }

            if (Schema::hasIndex('product_reviews', 'product_reviews_created_at_idx')) {
                $table->dropIndex('product_reviews_created_at_idx');
            }
        });

        Schema::table('shipments', function (Blueprint $table) {
            if (Schema::hasIndex('shipments', 'shipments_created_at_idx')) {
                $table->dropIndex('shipments_created_at_idx');
            }
        });

        Schema::table('refunds', function (Blueprint $table) {
            if (Schema::hasIndex('refunds', 'refunds_state_idx')) {
                $table->dropIndex('refunds_state_idx');
            }

            if (Schema::hasIndex('refunds', 'refunds_created_at_idx')) {
                $table->dropIndex('refunds_created_at_idx');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasIndex('invoices', 'invoices_state_idx')) {
                $table->dropIndex('invoices_state_idx');
            }

            if (Schema::hasIndex('invoices', 'invoices_created_at_idx')) {
                $table->dropIndex('invoices_created_at_idx');
            }
        });

        Schema::table('product_attribute_values', function (Blueprint $table) {
            if (Schema::hasIndex('product_attribute_values', 'pav_attr_product_idx')) {
                $table->dropIndex('pav_attr_product_idx');
            }
        });

        Schema::table('product_flat', function (Blueprint $table) {
            if (Schema::hasIndex('product_flat', 'product_flat_listing_idx')) {
                $table->dropIndex('product_flat_listing_idx');
            }

            if (Schema::hasIndex('product_flat', 'product_flat_channel_locale_idx')) {
                $table->dropIndex('product_flat_channel_locale_idx');
            }
        });

        Schema::table('search_terms', function (Blueprint $table) {
            if (Schema::hasIndex('search_terms', 'search_terms_term_channel_locale_idx')) {
                $table->dropIndex('search_terms_term_channel_locale_idx');
            }
        });

        Schema::table('cart', function (Blueprint $table) {
            if (Schema::hasIndex('cart', 'cart_customer_active_idx')) {
                $table->dropIndex('cart_customer_active_idx');
            }
        });

        Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasIndex('addresses', 'addresses_customer_type_idx')) {
                $table->dropIndex('addresses_customer_type_idx');
            }

            if (Schema::hasIndex('addresses', 'addresses_order_type_idx')) {
                $table->dropIndex('addresses_order_type_idx');
            }
        });

        Schema::table('core_config', function (Blueprint $table) {
            if (Schema::hasIndex('core_config', 'core_config_lookup_idx')) {
                $table->dropIndex('core_config_lookup_idx');
            }

            if (Schema::hasIndex('core_config', 'core_config_code_idx')) {
                $table->dropIndex('core_config_code_idx');
            }
        });
    }
};
