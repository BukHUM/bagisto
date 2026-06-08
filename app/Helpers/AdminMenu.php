<?php

namespace App\Helpers;

class AdminMenu
{
    /**
     * Bagisto admin icon classes for submenu items (core menu.php leaves these empty).
     *
     * @var array<string, string>
     */
    private const SUBMENU_ICONS = [
        // Sales
        'sales.orders' => 'icon-cart',
        'sales.shipments' => 'icon-ship',
        'sales.invoices' => 'icon-printer',
        'sales.refunds' => 'icon-refund',
        'sales.transactions' => 'icon-repeat',
        'sales.bookings' => 'icon-calendar',
        'sales.rma' => 'icon-order-back',
        'sales.rma.requests' => 'icon-list',
        'sales.rma.reasons' => 'icon-information',
        'sales.rma.rules' => 'icon-filter',
        'sales.rma.statuses' => 'icon-checked',
        'sales.rma.custom-fields' => 'icon-attribute',
        'sales.eu_withdrawals' => 'icon-exit',

        // Catalog
        'catalog.products' => 'icon-product',
        'catalog.categories' => 'icon-folder',
        'catalog.attributes' => 'icon-attribute',
        'catalog.families' => 'icon-attribute-block',

        // Customers
        'customers.customers' => 'icon-customer',
        'customers.groups' => 'icon-customer-2',
        'customers.reviews' => 'icon-star',
        'customers.gdpr_requests' => 'icon-information',

        // Marketing
        'marketing.promotions' => 'icon-promotion',
        'marketing.promotions.catalog_rules' => 'icon-promotion',
        'marketing.promotions.cart_rules' => 'icon-cart',
        'marketing.communications' => 'icon-mail',
        'marketing.communications.email_templates' => 'icon-mail',
        'marketing.communications.events' => 'icon-calendar',
        'marketing.communications.campaigns' => 'icon-mail',
        'marketing.communications.subscribers' => 'icon-customer',
        'marketing.search_seo' => 'icon-search',
        'marketing.search_seo.url_rewrites' => 'icon-repeat',
        'marketing.search_seo.search_terms' => 'icon-search',
        'marketing.search_seo.search_synonyms' => 'icon-list',
        'marketing.search_seo.sitemaps' => 'icon-folder',

        // Reporting
        'reporting.sales' => 'icon-sales',
        'reporting.customers' => 'icon-customer-2',
        'reporting.products' => 'icon-product-1',

        // Settings
        'settings.locales' => 'icon-language',
        'settings.currencies' => 'icon-cart',
        'settings.exchange_rates' => 'icon-repeat',
        'settings.inventory_sources' => 'icon-location',
        'settings.channels' => 'icon-store',
        'settings.users' => 'icon-customer',
        'settings.roles' => 'icon-setting',
        'settings.themes' => 'icon-image',
        'settings.taxes' => 'icon-list',
        'settings.taxes.tax_categories' => 'icon-folder',
        'settings.taxes.tax_rates' => 'icon-list',
        'settings.data_transfer' => 'icon-admin-export',
        'settings.data_transfer.imports' => 'icon-admin-export',

        // Beyondary
        'beyondary.storefront' => 'icon-store',
    ];

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function serializedItems(): array
    {
        $serialize = function ($item, bool $isChild = false) use (&$serialize) {
            $icon = $item->getIcon();

            if ($isChild) {
                $icon = self::resolveSubmenuIcon($item->getKey(), $icon);
            }

            return [
                'key' => $item->getKey(),
                'name' => $item->getName(),
                'url' => $item->getUrl(),
                'icon' => $icon,
                'active' => (bool) $item->isActive(),
                'children' => $item->haveChildren()
                    ? $item->getChildren()->map(fn ($child) => $serialize($child, true))->values()->all()
                    : [],
            ];
        };

        return menu()->getItems('admin')
            ->map(fn ($item) => $serialize($item))
            ->values()
            ->all();
    }

    public static function isHelpActive(): bool
    {
        return request()->routeIs('admin.help.index');
    }

    private static function resolveSubmenuIcon(string $key, string $icon): string
    {
        if ($icon !== '') {
            return $icon;
        }

        if (isset(self::SUBMENU_ICONS[$key])) {
            return self::SUBMENU_ICONS[$key];
        }

        return self::guessIconFromKey($key);
    }

    private static function guessIconFromKey(string $key): string
    {
        $leaf = str_contains($key, '.')
            ? substr($key, strrpos($key, '.') + 1)
            : $key;

        return match ($leaf) {
            'orders' => 'icon-cart',
            'shipments' => 'icon-ship',
            'invoices' => 'icon-printer',
            'refunds' => 'icon-refund',
            'products' => 'icon-product',
            'categories' => 'icon-folder',
            'attributes' => 'icon-attribute',
            'families' => 'icon-attribute-block',
            'customers' => 'icon-customer',
            'groups' => 'icon-customer-2',
            'reviews' => 'icon-star',
            'promotions' => 'icon-promotion',
            'communications' => 'icon-mail',
            'sales' => 'icon-sales',
            'locales' => 'icon-language',
            'channels' => 'icon-store',
            'users' => 'icon-customer',
            'roles' => 'icon-setting',
            'themes' => 'icon-image',
            'imports' => 'icon-admin-export',
            'storefront' => 'icon-store',
            default => 'icon-list',
        };
    }
}
